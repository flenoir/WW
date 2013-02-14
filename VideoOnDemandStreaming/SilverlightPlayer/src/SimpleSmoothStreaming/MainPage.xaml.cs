using System;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Documents;
using System.Windows.Ink;
using System.Windows.Input;
using System.Windows.Media;
using System.Windows.Media.Animation;
using System.Windows.Shapes;
using Microsoft.Web.Media.SmoothStreaming;
using System.Windows.Threading;
using System.Windows.Interop;
using System.Collections;
using System.Collections.Generic;
using System.Linq;
using System.Reflection;

namespace SimpleSmoothStreaming
{
    public partial class MainPage : UserControl
    {
        DispatcherTimer seekbarTimer = new DispatcherTimer();
        Boolean manifestChanged = true;
        Boolean manifestLoad = true;
        double playerWidth;
        double playerHeight;
        double layoutRootHeight;
        double layoutRootWidth;
        double currentWidth;
        double currentHeight;
        ulong highRate = 200000;
        
        Thickness oldMargins;

        public MainPage()
        {
            InitializeComponent();

            SmoothPlayer.ManifestReady += new EventHandler<EventArgs>(SmoothPlayer_ManifestReady);
            SmoothPlayer.MediaOpened += new RoutedEventHandler(SmoothPlayer_MediaOpened);
            SmoothPlayer.MediaEnded += new RoutedEventHandler(SmoothPlayer_MediaEnded);
            SmoothPlayer.MediaFailed += new EventHandler<ExceptionRoutedEventArgs>(SmoothPlayer_MediaFailed);
            SmoothPlayer.SmoothStreamingErrorOccurred += new EventHandler<SmoothStreamingErrorEventArgs>(SmoothPlayer_SmoothStreamingErrorOccurred);
            SmoothPlayer.ClipError += new EventHandler<ClipEventArgs>(SmoothPlayer_ClipError);
            SmoothPlayer.DownloadTrackChanged += new EventHandler<TrackChangedEventArgs>(SmoothPlayer_TrackChanged);
            SmoothPlayer.PlaybackTrackChanged += new EventHandler<TrackChangedEventArgs>(SmoothPlayer_TrackChanged);
            App.Current.Host.Content.FullScreenChanged += new EventHandler(Content_FullScreenChanged);

            oldMargins = new Thickness(SmoothPlayer.Margin.Left, SmoothPlayer.Margin.Top, SmoothPlayer.Margin.Right, SmoothPlayer.Margin.Bottom);
            playerWidth = SmoothPlayer.Width;
            playerHeight = SmoothPlayer.Height;
            layoutRootHeight = LayoutRoot.Height;
            layoutRootWidth = LayoutRoot.Width;
            VolumeBar.IsEnabled = false;
            fullScreenButton.Visibility = System.Windows.Visibility.Collapsed;

            SLVersion.Text = "Silverlight v" + Environment.Version.ToString() ;
        }

        void UserControl_Loaded(object sender, RoutedEventArgs e)
        {
            String url = ManifestURL.Text;
            //SmoothPlayer.SmoothStreamingSource = new Uri(url, UriKind.Absolute);
            SmoothPlayer.Volume = .5;
            VolumeBar.Value = 5;
        }

        public class Bitrate
        {
            public ulong bitrate { get; set; }
            public string display { get; set; }
        }

        void SmoothPlayer_ManifestReady(object sender, EventArgs e)
        {
            OutPut.Text = "";
            SeekBar.Maximum = SmoothPlayer.EndPosition.TotalSeconds;
            SmoothPlayer.Volume = VolumeBar.Value * .1;
            PlayButton.IsEnabled = true;
            PauseButton.Content = "Pause";
            currentWidth = this.Width;
            currentHeight = this.Height;

            if (!manifestChanged)
            {
                PlayButton.Content = "Connect";
                BWCombo.IsEnabled = true;
            }

            foreach (SegmentInfo segment in SmoothPlayer.ManifestInfo.Segments)
            {
                IList<StreamInfo> streamInfoList = segment.AvailableStreams;

                foreach (StreamInfo stream in streamInfoList)
                {
                    if (stream.Type == MediaStreamType.Video)
                    {
                        List<TrackInfo> tracks = new List<TrackInfo>();

                        tracks = stream.AvailableTracks.ToList<TrackInfo>();

                        if (manifestLoad)
                        {
                            List<Bitrate> bitRates = new List<Bitrate>();

                            ulong highest = 0;
                            int selectThis = 0;

                            for (int i = 0; i < tracks.Count; i++)
                            {
                                if (tracks[i].Bitrate > highest)
                                {
                                    selectThis = i;
                                    highRate = tracks[i].Bitrate + 1;
                                }
                                bitRates.Add(new Bitrate() { bitrate = tracks[i].Bitrate + 1, display = Math.Round(Convert.ToDecimal((tracks[i].Bitrate * .001))).ToString() + "kbs" });
                            }
                            bitRates.Add(new Bitrate() { bitrate = highRate + 1, display = "Auto" });
                            try
                            {
                                BWCombo.ItemsSource = bitRates;
                            }
                            catch { }

                            if (bitRates.Count < 3)
                            {
                                BWCombo.Visibility = System.Windows.Visibility.Collapsed;
                            }
                            else
                            {
                                BWCombo.Visibility = System.Windows.Visibility.Visible;
                            }

                            BWCombo.DisplayMemberPath = "display";
                            BWCombo.SelectedIndex = bitRates.Count-1;
                          
                            if (manifestLoad)
                            {
                                manifestLoad = false;

                                if ((String)PlayButton.Content == "Disconnect")
                                {
                                    SmoothPlayer.SmoothStreamingSource = null;
                                    BWCombo.IsEnabled = true;
                                    PlayButton.Content = "Connect";
                                }                       
                            }
                        }

                        IList<TrackInfo> allowedTracks = tracks.Where((ti) => ti.Bitrate < highRate).ToList();
                        System.Diagnostics.Debug.WriteLine(highRate.ToString());
                        stream.SelectTracks(allowedTracks, false);
                    }
                }
            }
        }

        void SmoothPlayer_MediaOpened(object sender, EventArgs e)
        {
            /*
            double frameAspectRatio = 640 / 320;
            double videoWidth = SmoothPlayer.NaturalVideoWidth;
            double videoHeight = SmoothPlayer.NaturalVideoHeight;
            double videoAspectRatio = videoWidth / videoHeight;

            playerHeight = currentHeight;
            playerWidth = currentWidth;

            if (videoAspectRatio > frameAspectRatio)
            {
                playerHeight = currentHeight / videoAspectRatio;
            }
            else
            {
                playerWidth = currentHeight * videoAspectRatio;
            }

            SmoothPlayer.Height = playerHeight;
            SmoothPlayer.Width = playerWidth;

            */
            if (manifestChanged)
            {
                manifestChanged = false;
                PlayButton_Click(null, null);
            }
            seekbarTimer.Interval = TimeSpan.FromMilliseconds(200);
            seekbarTimer.Start();
            seekbarTimer.Tick += new EventHandler(timer_Tick);
        }

        void SmoothPlayer_MediaEnded(object sender, EventArgs e)
        {
            PlayButton_Click(null, null);
        }

        void SmoothPlayer_MediaFailed(object sender, ExceptionRoutedEventArgs e)
        {
            OutPut.Text = "Media Error: " + e.ErrorException.Message;
            reset();
        }

        void SmoothPlayer_SmoothStreamingErrorOccurred(object sender, SmoothStreamingErrorEventArgs e)
        {
            OutPut.Text = "Streaming Error: " + e.ErrorMessage;
            reset();
        }

        void SmoothPlayer_ClipError(object sender, ClipEventArgs e)
        {
            OutPut.Text = "Clip Error: " + e.Context.CurrentClipState.ToString();
        }

        void reset()
        {
            manifestChanged = true;
            manifestLoad = true;
            PlayButton.Content = "Connect";
            PlayButton.IsEnabled = true;
            BWCombo.Visibility = System.Windows.Visibility.Collapsed;
            SmoothPlayer.SmoothStreamingSource = null;
            BitRate.Text = "0";
            BWCombo.IsEnabled = true;
            VolumeBar.IsEnabled = false;
            fullScreenButton.Visibility = System.Windows.Visibility.Collapsed;
           
        }

        void SmoothPlayer_TrackChanged(object sender, TrackChangedEventArgs e)
        {
            BitRate.Text = Math.Round(Convert.ToDecimal((e.NewTrack.Bitrate * .001))).ToString() + "kbs";
        }

        void timer_Tick(object sender, EventArgs e)
        {
            SeekBar.Value = SmoothPlayer.Position.TotalSeconds;
            // System.Diagnostics.Debug.WriteLine(SeekBar.Value);
        }

        void PlayButton_Click(object sender, RoutedEventArgs e)
        {
            OutPut.Text = "";

            if (manifestChanged)
            {
                String url = ManifestURL.Text;
                SmoothPlayer.SmoothStreamingSource = new Uri(url, UriKind.Absolute);
                PlayButton.IsEnabled = false;
                SeekBar.IsEnabled = false;
                PauseButton.IsEnabled = false;
                RewButton.IsEnabled = false;
                return;
            }

            if ((String)PlayButton.Content == "Connect")
            {
                SmoothPlayer.Play();
                BWCombo.IsEnabled = false;
                VolumeBar.IsEnabled = true;
                fullScreenButton.Visibility = System.Windows.Visibility.Visible;
                PlayButton.Content = "Disconnect";
                PauseButton.IsEnabled = true;
                SeekBar.IsEnabled = true;
                RewButton.IsEnabled = true;
                fullScreenButton.Visibility = System.Windows.Visibility.Visible;
            }
            else if ((String)PlayButton.Content == "Disconnect")
            {
                SmoothPlayer.Stop();
                seekbarTimer.Stop();
                SeekBar.Value = 0;
                SeekBar.IsEnabled = false;
                VolumeBar.IsEnabled = false;
                fullScreenButton.Visibility = System.Windows.Visibility.Collapsed;
                PlayButton.Content = "Connect";
                PauseButton.Content = "Pause";
                PauseButton.IsEnabled = false;
                RewButton.IsEnabled = false;
                BWCombo.IsEnabled = true;
                BitRate.Text = "0";
                fullScreenButton.Visibility = System.Windows.Visibility.Collapsed;
                SmoothPlayer.SmoothStreamingSource = null;
                manifestChanged = true;
            }

        }

        void PauseButton_Click(object sender, RoutedEventArgs e)
        {
            if (PauseButton.Content.ToString() == "Pause")
            {
                SmoothPlayer.Pause();
                seekbarTimer.Stop();

                PauseButton.Content = "Play";
            }
            else
            {
                SmoothPlayer.Play();
                seekbarTimer.Start();

                PauseButton.Content = "Pause";
            }
        }

        void Content_FullScreenChanged(object sender, EventArgs e)
        {
            Boolean isFullScreen = Application.Current.Host.Content.IsFullScreen;
            
            if (isFullScreen)
            {
                SmoothPlayer.Width = Application.Current.Host.Content.ActualWidth;
                SmoothPlayer.Height = Application.Current.Host.Content.ActualHeight;
                LayoutRoot.Width = Application.Current.Host.Content.ActualWidth;
                LayoutRoot.Height = Application.Current.Host.Content.ActualHeight;
                controlsContainer.Visibility = Visibility.Collapsed;
                SLVersion.Visibility = Visibility.Collapsed;
                fullScreenButton.Visibility = Visibility.Collapsed;
                //SmoothPlayer.Background = new SolidColorBrush(Colors.Black);
                SmoothPlayer.Margin = new Thickness(0, 0, 0, 0);
            }
            else
            {
                SmoothPlayer.Width = playerWidth;
                SmoothPlayer.Height = playerHeight;
                LayoutRoot.Width = layoutRootWidth;
                LayoutRoot.Height = layoutRootHeight;
                controlsContainer.Visibility = Visibility.Visible;
                SLVersion.Visibility = Visibility.Visible;
                fullScreenButton.Visibility = Visibility.Visible;
                //SmoothPlayer.Background = new SolidColorBrush(Colors.White);
                SmoothPlayer.Margin = oldMargins;
            }
        }

        void Fullscreen_Click(object sender, RoutedEventArgs e)
        {
            Application.Current.Host.Content.IsFullScreen = (Application.Current.Host.Content.IsFullScreen) ? false : true;

            /*
            if (!Application.Current.Host.Content.IsFullScreen)
            {
                Grid.SetRowSpan(SmoothPlayer, 1);
                SmoothPlayer.Width = playerWidth;
                SmoothPlayer.Height = playerHeight;
                grid2.Visibility = System.Windows.Visibility.Visible;
                controlsContainer.Visibility = Visibility.Visible;
                SmoothPlayer.Background = new SolidColorBrush(Colors.White);
                SmoothPlayer.Margin = new Thickness(10, 10, 0, 0);
            }
            else
            {
                Grid.SetRowSpan(SmoothPlayer, 2);
                SmoothPlayer.Width = this.Width;
                SmoothPlayer.Height = this.Height;
                grid2.Visibility = System.Windows.Visibility.Collapsed;
                controlsContainer.Visibility = Visibility.Collapsed ;
                SmoothPlayer.Background = new SolidColorBrush(Colors.Black);
                SmoothPlayer.Margin = new Thickness(0, 0, 0, 0);
            }
             * */
        }

        void SeekBar_MouseLeftButtonUp(object sender, MouseButtonEventArgs e)
        {
            SmoothPlayer.Position = TimeSpan.FromSeconds(SeekBar.Value);
        }

        void SeekBar_MouseEnter(object sender, MouseEventArgs e)
        {
            seekbarTimer.Stop();
        }

        void SeekBar_MouseLeave(object sender, MouseEventArgs e)
        {
           if (PlayButton.Content.ToString() == "Stop")
                seekbarTimer.Start();
        }

        void VolumeBar_ValueChanged(object sender, RoutedPropertyChangedEventArgs<double> e)
        {
            SmoothPlayer.Volume = VolumeBar.Value * .1;
        }

        void RewButton_Click(object sender, RoutedEventArgs e)
        {
            SmoothPlayer.Position = TimeSpan.FromSeconds(0);
            SeekBar.Value = 0;
        }

        private void ManifestURL_KeyDown(object sender, KeyEventArgs e)
        {
            manifestChanged = true;
            manifestLoad = true;
            PlayButton.IsEnabled = true;
        }

        private void BWCombo_SelectionChanged(object sender, SelectionChangedEventArgs e)
        {
            Bitrate br = (Bitrate)BWCombo.SelectedItem;
            highRate = br.bitrate;
        }

        private void Reload_Click(object sender, RoutedEventArgs e)
        {
            System.Windows.Browser.HtmlPage.Window.Navigate(new Uri("", UriKind.RelativeOrAbsolute));
        }
    }
}