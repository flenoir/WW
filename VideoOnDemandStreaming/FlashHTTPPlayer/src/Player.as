// VOD
// VOD
// VOD
// VOD
//4.5 SDK can be found at http://download.macromedia.com/pub/flex/sdk/flex_sdk_4.5.zip
//
package
{
	import flash.events.Event;
	import flash.events.FullScreenEvent;
	import flash.events.IOErrorEvent;
	import flash.events.MouseEvent;
	import flash.geom.Rectangle;
	import flash.net.Responder;
	import flash.net.URLLoader;
	import flash.net.URLRequest;
	import flash.system.Capabilities;
	import flash.system.Security;
	import flash.display.StageDisplayState;
	
	import mx.containers.Canvas;
	import mx.controls.Button;
	import mx.controls.Text;
	import mx.controls.TextInput;
	import mx.controls.sliderClasses.Slider;
	import mx.events.FlexEvent;
	import mx.events.SliderEvent;
	
	import org.osmf.containers.MediaContainer;
	import org.osmf.events.LoadEvent;
	import org.osmf.events.MediaElementEvent;
	import org.osmf.events.MediaErrorEvent;
	import org.osmf.events.MediaPlayerCapabilityChangeEvent;
	import org.osmf.events.PlayEvent;
	import org.osmf.events.TimeEvent;
	import org.osmf.media.DefaultMediaFactory;
	import org.osmf.media.MediaElement;
	import org.osmf.media.MediaPlayer;
	import org.osmf.media.URLResource;
	import org.osmf.net.DynamicStreamingItem;
	import org.osmf.net.DynamicStreamingResource;
	import org.osmf.samples.MediaContainerUIComponent;
	import org.osmf.traits.PlayState;
	import org.osmf.utils.*;
	import org.osmf.utils.Version;
	
	import spark.components.Application;
	import spark.components.Label;
	import spark.components.TextArea;
	
	public class Player extends Application
	{
		Security.LOCAL_TRUSTED;
		
		[Bindable]
		public var isConnected:Boolean = false;
		
		private var mediaElement:MediaElement;
		private var factory:DefaultMediaFactory = new DefaultMediaFactory();
		private var player:MediaPlayer = new MediaPlayer();
		private var isScrubbing:Boolean = false;
		private var fullscreenCapable:Boolean = false;
		private var hardwareScaleCapable:Boolean = false;
		private var saveVideoObjX:Number;
		private var saveVideoObjY:Number;
		private var saveVideoObjW:Number;
		private var saveVideoObjH:Number;
		private var saveStageW:Number;
		private var saveStageH:Number;
		private var adjVideoObjW:Number;
		private var adjVideoObjH:Number;
		private var streamName:String;
		private var PlayVersionMin:Boolean;
		public var prompt:Text;
		public var warn:Text;
		public var connectStr:TextInput;
		public var playerVersion:Text;
		public var videoContainer:MediaContainerUIComponent;
		public var videoBackground:Label;
		public var videoFrame:spark.components.TextArea;
		public var connectButton:Button;
		public var doPlay:Button;
		public var seekBar:Slider;
		public var volumeLevel:Slider;
		public var doRewind:Button;
		public var doFullscreen:Button;
		private var dynResource:DynamicStreamingResource;
		public var backdrop:Canvas;
		
		public function Player()
		{
			super();
			this.addEventListener(FlexEvent.APPLICATION_COMPLETE,init);
		}
		
		private function init(event:FlexEvent):void
		{	
			stage.align="TL";
			stage.scaleMode="noScale";
			
			this.setStyle("backgroundColor","#FFFFFF");
			
			doFullscreen.addEventListener(MouseEvent.CLICK,enterFullscreen);
			stage.addEventListener(FullScreenEvent.FULL_SCREEN, enterLeaveFullscreen);
			connectButton.addEventListener(MouseEvent.CLICK,connect);
			doPlay.addEventListener(MouseEvent.CLICK,togglePlayPause);
			doRewind.addEventListener(MouseEvent.CLICK,rewind);
			volumeLevel.addEventListener(SliderEvent.CHANGE,volumeChange);
			seekBar.addEventListener(SliderEvent.CHANGE,movieSeek);
			seekBar.addEventListener(MouseEvent.MOUSE_DOWN, function(event:MouseEvent):void{
				isScrubbing=true;
			});
			this.addEventListener(MouseEvent.MOUSE_UP, function(event:MouseEvent):void{
				isScrubbing=false;
			});	
			seekBar.maximum = 0;
			
			connectStr.text = "http://localhost:1935/vod/mp4:sample.mp4/manifest.f4m";
			OSMFSettings.enableStageVideo = true;

			checkVersion();
			
			// *********************** stream examples ******************//
			// http://localhost:1935/vod/mp4:sample.mp4/manifest.f4m
			// http://localhost:1935/vod/smil:streamNames.smil/manifest.f4m (server-side smil)
			// rtmp://localhost:1935/vod/mp4:sample.mp4
			// rtmp://localhost:1935/vod/streamNames.xml (Dynamic Streams)
				
			videoContainer.container = new MediaContainer();
			PlayVersionMin = testVersion(10, 1, 0, 0);				
			playerVersion.text = Capabilities.version + " (Flash-OSMF " + org.osmf.utils.Version.version + ")";	

			saveStageW = stage.width;
			saveStageH = stage.height;
			
			saveVideoObjX = videoContainer.x;
			saveVideoObjY = videoContainer.y;
			saveVideoObjW = videoContainer.width;
			saveVideoObjH = videoContainer.height;
		}
			
		private function connect(event:MouseEvent):void // Play button (connectButton)
		{	
			if (connectButton.label == "Disconnect")
			{
				stopAll();
				videoBackground.visible=true;
				return;
			}
			var ok:Boolean = checkVersion();
			if (!ok)
			{
				stopAll();
				return;
			}
			
			if (connectStr.text.toLowerCase().indexOf("rtmp://")>-1 && connectStr.text.toLowerCase().indexOf(".xml")>-1)
				streamName = connectStr.text.substring(connectStr.text.lastIndexOf("/")+1, connectStr.text.length);
			
			if (streamName==null) 
			{
				loadStream();
			}
			else if (streamName.toLowerCase().indexOf(".xml") > 0)
			{	
				loadVector(streamName); // load Dynamic stream items
			}
		}
		
		private function loadStream():void
		{	
			prompt.text = "";
			
			mediaElement = factory.createMediaElement(new URLResource(connectStr.text));
			
			if (dynResource != null)
				mediaElement.resource=dynResource;
			
			player = new MediaPlayer();
			
			player.media = mediaElement;	
			videoContainer.container.addMediaElement(mediaElement);	
			player.addEventListener(TimeEvent.CURRENT_TIME_CHANGE, videoTimeChange);
			isConnected = true;
			
			mediaElement.addEventListener(MediaErrorEvent.MEDIA_ERROR,function(event:MediaErrorEvent):void
			{
				trace("Media Error: " + event.error.detail);
				stopAll();
				prompt.text = event.error.message + " " + event.error.detail;
				return;
			});
			
			player.addEventListener(PlayEvent.PLAY_STATE_CHANGE, function(event:PlayEvent):void
			{
				trace("Play event: " + event.playState);
				if (event.playState == PlayState.STOPPED)	
					stopAll();
			});
			
			player.addEventListener(MediaPlayerCapabilityChangeEvent.CAN_PLAY_CHANGE, function(event:MediaPlayerCapabilityChangeEvent):void
			{
				isConnected = event.enabled;
			});
			
			player.autoPlay = true;
			connectButton.label  = "Disconnect";
			doPlay.label = "Pause";
			videoBackground.visible=false;			
		}
			
		private function videoTimeChange(event:TimeEvent):void
		{
			if (isScrubbing)
				return;
			
			seekBar.value = event.time;
			
			if (seekBar.maximum == 0 && player.duration > 0)
			{
				seekBar.maximum = player.duration + 4;
			}
		}
		
		private function volumeChange(event:SliderEvent):void
		{
			player.volume = event.value;
		}
		
		private function movieSeek(event:SliderEvent):void
		{
			if (player.canSeek)
				player.seek(event.value);
		}
		
		private function rewind(event:MouseEvent):void
		{
			if (player.canSeek)
				player.seek(0);
	
			seekBar.value = 0;
		}
		
		private function togglePlayPause(event:MouseEvent):void
		{
			if (player.playing)
			{
				player.pause();
				doPlay.label = "Play";
			}
			else
			{
				player.play();
				doPlay.label = "Pause";
			}
		}
		
		private function stopAll():void
		{	
			if (player!=null)
			{
				player.removeEventListener(TimeEvent.CURRENT_TIME_CHANGE, videoTimeChange);
				if (player.playing)
					player.stop();
			}
			
			seekBar.value = 0;
			seekBar.maximum = 0;
			isConnected = false;
			
			if (mediaElement != null)
				videoContainer.container.removeMediaElement(mediaElement);
			
			mediaElement = null;
			connectButton.label = "Connect";
			player = null;
			prompt.text = "";
			
			dynResource = null;
		}
		
		private function loadVector(streamName:String):void
		{
			var url:String = connectStr.text;
			
			if (url.indexOf("rtmp://") != 0)
			{
				loadStream();
				return;
			}
			
			var loader:URLLoader=new URLLoader();
			loader.addEventListener(Event.COMPLETE,xmlHandler);
			loader.addEventListener(IOErrorEvent.IO_ERROR,xmlIOErrorHandler)			
			
			var request:URLRequest=new URLRequest();
			var requestURL:String = streamName;
			request.url = requestURL;
			
			loader.load(request)

		}
		
		private function xmlHandler(event:Event):void
		{
			var streamsVector:Vector.<DynamicStreamingItem> = new Vector.<DynamicStreamingItem>();
			var streamNames:XML;	
			var loader:URLLoader=URLLoader(event.target);
			streamNames = new XML(loader.data);
			
			var videos:XMLList = streamNames..video;
			
			for (var i:int=0; i<videos.length(); i++)
			{
				var video:XML = videos[i];
				var bitrate:String = video.attribute("system-bitrate");
				var item:DynamicStreamingItem = new DynamicStreamingItem(video.@src,Number(bitrate), video.@width, video.@height);
				streamsVector.push(item);
			}
			if (videos.length()>0)
			{
				dynResource = new DynamicStreamingResource(connectStr.text);				
				dynResource.streamItems = streamsVector;
			}
			loadStream();
		}
		
		private function enterLeaveFullscreen(event:FullScreenEvent):void
		{
			trace("enterLeaveFullscreen: "+ event.fullScreen);
			if (!event.fullScreen)
			{
				// reset back to original state
				//this.setStyle("backgroundColor","#FFFFFF");
				stage.scaleMode = "noScale";
				stage.displayState = StageDisplayState.NORMAL; 
				videoFrame.visible=true;
				videoContainer.width = saveVideoObjW;
				videoContainer.height = saveVideoObjH;
				videoContainer.x = saveVideoObjX;
				videoContainer.y = saveVideoObjY;
				backdrop.visible=true;
			}
		}

		
		private function enterFullscreen(event:MouseEvent):void
		{
			trace("enterFullscreen: "+hardwareScaleCapable);
			
			//this.setStyle("backgroundColor","#000000");
			
			videoFrame.visible=false;
			if (hardwareScaleCapable)
			{
				// best settings for hardware scaling				
				// grab the portion of the stage that is just the video frame
				stage["fullScreenSourceRect"] = new Rectangle(
					videoContainer.x, videoContainer.y, 
					videoContainer.width, videoContainer.height);
			}
			else
			{
				stage.scaleMode = "noBorder";
				
				var videoAspectRatio:Number = videoContainer.width/videoContainer.height;
				var stageAspectRatio:Number = saveStageW/saveStageH;
				var screenAspectRatio:Number = Capabilities.screenResolutionX/Capabilities.screenResolutionY;
				
				// calculate the width and height of the scaled stage
				var stageObjW:Number = saveStageW;
				var stageObjH:Number = saveStageH;
				if (stageAspectRatio > screenAspectRatio)
					stageObjW = saveStageH*screenAspectRatio;
				else
					stageObjH = saveStageW/screenAspectRatio;
				
				// calculate the width and height of the video frame scaled against the new stage size
				var fsvideoContainerW:Number = stageObjW;
				var fsvideoContainerH:Number = stageObjH;
				
				if (videoAspectRatio > screenAspectRatio)
					fsvideoContainerH = stageObjW/videoAspectRatio;			
				else
					fsvideoContainerW = stageObjH*videoAspectRatio;
				// scale the video object
				videoContainer.width = fsvideoContainerW;
				videoContainer.height = fsvideoContainerH;
				videoContainer.x = (stageObjW-fsvideoContainerW)/2.0;
				videoContainer.y = (stageObjH-fsvideoContainerH)/2.0;
				
			}
			stage.displayState = StageDisplayState.FULL_SCREEN;	
		}
		
		private function xmlIOErrorHandler(event:IOErrorEvent):void
		{
			trace("XML IO Error: " + event.target);
			prompt.text = "XML IO Error: " + event.text;	
		}
		
		private function checkVersion():Boolean
		{
			PlayVersionMin = testVersion(10, 1, 0, 0);
			hardwareScaleCapable = testVersion(9, 0, 60, 0);
			if (!PlayVersionMin && connectStr.text.indexOf(".f4m") > 0)
			{
				prompt.text = "Sanjose Streaming not support in this Flash version.";
				return false;
			}
			else
			{
				prompt.text="";
				return true;
			}
		}
		
		private function testVersion(v0:Number, v1:Number, v2:Number, v3:Number):Boolean
		{
			var version:String = Capabilities.version;
			var index:Number = version.indexOf(" ");
			version = version.substr(index+1);
			var verParts:Array = version.split(",");
			
			var i:Number;
			
			var ret:Boolean = true;
			while(true)
			{
				if (Number(verParts[0]) < v0)
				{
					ret = false;
					break;
				}
				else if (Number(verParts[0]) > v0)
					break;
				
				if (Number(verParts[1]) < v1)
				{
					ret = false;
					break;
				}
				else if (Number(verParts[1]) > v1)
					break;
				
				if (Number(verParts[2]) < v2)
				{
					ret = false;
					break;
				}
				else if (Number(verParts[2]) > v2)
					break;
				
				if (Number(verParts[3]) < v3)
				{
					ret = false;
					break;
				}
				break;
			}
			trace("testVersion: "+Capabilities.version+">="+v0+","+v1+","+v2+","+v3+": "+ret);	
			return ret;
		}
	}
}