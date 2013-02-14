using System;
using System.Net;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Documents;
using System.Windows.Ink;
using System.Windows.Input;
using System.Windows.Media;
using System.Windows.Media.Animation;
using System.Windows.Shapes;
using System.Windows.Controls.Primitives;

namespace SimpleSmoothStreaming
{
    public class CustomSlider : Slider
    {
        private Thumb _HorizontalThumb;
        private FrameworkElement left;
        private FrameworkElement right;

        public CustomSlider() { }

        public override void OnApplyTemplate()
        {
            base.OnApplyTemplate();

            _HorizontalThumb = GetTemplateChild("HorizontalThumb") as Thumb;

            left = GetTemplateChild("LeftTrack") as FrameworkElement;
            right = GetTemplateChild("RightTrack") as FrameworkElement;

            if (left != null) left.MouseLeftButtonDown += new MouseButtonEventHandler(OnMoveThumbToMouse);
            if (right != null) right.MouseLeftButtonDown += new MouseButtonEventHandler(OnMoveThumbToMouse);
        }

        private void OnMoveThumbToMouse(object sender, MouseButtonEventArgs e)
        {
            Point p = e.GetPosition(this);

            Value = (p.X - (_HorizontalThumb.ActualWidth / 2)) / (ActualWidth - _HorizontalThumb.ActualWidth) * Maximum;
        }
    }
}