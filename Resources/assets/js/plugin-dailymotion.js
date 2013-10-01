function dailymotionPlayer(musicPlayer) {
	
	this.name = "Dailymotion";
	this.cancelRequested = false;
	this.interval;
	this.musicPlayer = musicPlayer;
	this.currentState = null;
	this.dmplayer = null;
	this.widgetElement = $("#dailymotionContainer");

	var self = this;
	
	this.initPlayer = function(videoId){
		self.dmplayer = DM.player(document.getElementById('dailymotion-player'), {video: videoId,width:'100%',height:'200'});
		self.dmplayer.addEventListener("apiready", function(e){self.onDailymotionAPIReady(e)});
	}


	this.requestCancel=function(){
		self.cancelRequested=true;
	}
	

	this.hideWidget=function(){
		if(self.widgetElement!=null){
			loggerDailymotion.debug('hide dailymotion player in dailymotionplugin');
			self.widgetElement.addClass('fakeHide');
		}
	}
	this.showWidget = function(){
		if(self.widgetElement!=null){
			loggerDailymotion.debug('show dailymotion player in dailymotionplugin');
			self.widgetElement.removeClass('fakeHide');
		}
	}
	this.play = function(item) {
		var videoId = item.entryId;
		
		if (self.dmplayer == null) {
			self.initPlayer(videoId);
		
		} else {
			if(self.currentState == 1){
				self.stop();
			}
			self.dmplayer.load(videoId);

		}
		

	};
	this.stop = function(){
		loggerDailymotion.debug('call stop in dailymotion plugin');
		
		if(self.dmplayer!=null){
			self.dmplayer.pause();
		}
		//window.clearInterval(self.interval);
	}
	
	this.pause = function(){
		if(self.dmplayer!=null){
			self.dmplayer.pause();
		}
	}
	
	this.resume = function(){
		if(self.dmplayer!=null){
			self.dmplayer.togglePlay();
		}
	}
	
	this.setVolume = function(value){
		loggerDailymotion.debug('call setVolume dailymotion');
		if(self.dmplayer!=null){
			self.dmplayer.setVolume(value/100);
		}
	}
	
	this.playHelper = function() {
		if(self.dmplayer!=null){
			
			self.dmplayer.play();
			self.setVolume(self.musicPlayer.volume);
		}
	};
	
	this.onDailymotionAPIReady = function(e){
		self.dmplayer.addEventListener('play',function(e){
			self.onPlay(e);
		});
		self.dmplayer.addEventListener('ended',function(e){
			self.onEnded(e);
		});
		self.dmplayer.addEventListener('pause',function(e){
			self.onPause(e);
		});
		self.playHelper();
	}

	
	this.onEnded = function(){
		self.clearInterval();
		self.musicPlayer.unbinCursorStop();
		self.musicPlayer.cursor.progressbar( "value",0 );
		loggerDailymotion.debug('Call next from dailymotion plugin');
		self.musicPlayer.next();
	}
	
	this.onPause = function(e){
		loggerDailymotion.debug('Clear interval');
		self.clearInterval();
	}
	
	this.onPlay = function(e){
		if(self.cancelRequested==false){
			//self.showWidget();
			self.musicPlayer.enableControls();
			self.musicPlayer.cursor.slider("value", 0);
			
			var duration = self.dmplayer.duration;
			var loaded = self.dmplayer.bufferedTime;
			self.musicPlayer.cursor.slider("option", "max", duration).progressbar({value:(loaded/duration)*100,});	
			
			self.musicPlayer.bindCursorStop(function(value) {
				self.dmplayer.seek(value);
			});
			
			loggerDailymotion.debug('Create interval');
			self.createCursorInterval(1000);
		}else{
			self.cancelRequested=false;
			self.stop();
		}		
		
	}

	this.clearInterval=function(){
		loggerDailymotion.debug('clear interval: '+self.interval);
		window.clearInterval(self.interval);
	};
	
	this.createCursorInterval=function(delay){
		self.clearInterval();
		self.interval = window.setInterval(function() {
			//loggerDailymotion.debug('update dailymotion cursor');
			var secondLoaded=self.dmplayer.bufferedTime;
			var duration = self.dmplayer.duration;
			self.musicPlayer.cursor.progressbar('value',(secondLoaded/duration)*100);
			if(self.musicPlayer.controls.volumeSlider.data('isdragging')==false){
		    self.musicPlayer.controls.volumeSlider.slider("value", self.dmplayer.volume);
			}
			if(self.musicPlayer.cursor.data('isdragging')==false){
				self.musicPlayer.cursor.slider("value", self.dmplayer.currentTime);
						
			}
		}, delay);
		loggerDailymotion.debug('Interval : '+self.interval+' created');
	};
	
}
iconMap['dm'] = '/bundles/cogimixdailymotion/images/dm-icon.png';
$("body").on('musicplayerReady',function(event){
	event.musicPlayer.addPlugin('dm',new dailymotionPlayer(event.musicPlayer));
});


	
