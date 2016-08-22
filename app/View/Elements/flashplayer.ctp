<script type="text/javascript" src="js/swfobject.js"></script>
<script type="text/javascript">
// For version detection, set to min. required Flash Player version, or 0 (or 0.0.0), for no version detection. 
var swfVersionStr = "10.1.0";
// To use express install, set to playerProductInstall.swf, otherwise the empty string. 
var xiSwfUrlStr = "playerProductInstall.swf";

var flashvars = {};
///一般参数设置///
flashvars.logging=true;
flashvars.logLevel="all"; 
flashvars.plugin=false;
flashvars.volume=50;
flashvars.loop=false;
//flashvars.autoLoad=true;
flashvars.autoPlay=false;
flashvars.streamType="seekableVod";//"slicedMedia";//"seekableVod";//"seekableVod"//p2pliveS//slicedMedia
flashvars.smoothing=true;   
//flashvars.nonDisplay="initMedia";
flashvars.initMedia="<?php echo $image_sourceurl;?>";

///播放器地址设置////////

flashvars.url=encodeURIComponent('<?php echo $file_sourceurl;?>');
//flashvars.url=encodeURIComponent('clip://j:{"duration":31,"title":"test","host":"","clips":[{"duration":31,"title":"001","urls":["http:\/\/localhost\/20120124145871.mp4"]}],"formats":["\u6807\u6e05"]}');
var params = {};
params.quality = "high"; 
params.allowscriptaccess = "sameDomain";
params.allowfullscreen = "true";
params.wmode = "transparent";
var attributes = {};
attributes.id = "SoPlayer";
attributes.name = "SoPlayer";

swfobject.embedSWF(
    "SoPlayer2.swf", "flashContent", 
    "<?php echo $width?>", "<?php echo $height?>", 
    swfVersionStr, xiSwfUrlStr, 
    flashvars, params, attributes);
// JavaScript enabled so display the flashContent div in case it is not replaced with a swf object.
//swfobject.createCSS("#flashContent", "");
</script>
<div id="flashContent" url=""></div>


