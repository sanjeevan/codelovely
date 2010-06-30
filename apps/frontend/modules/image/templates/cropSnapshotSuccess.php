<h1>Crop this snapshot</h1>

<h2>Crop image to 400x300px</h2>
<img src="<?php echo $file->getUrl(); ?>" id="crop" />


<h2>Quick preview</h2>
<div style="float: left; width:400px;height:300px;overflow:hidden;margin-left:5px;">
  <img class="photo" src="<?php echo $file->getUrl(); ?>" id="preview" />
</div>
<div style="margin-left: 420px">
  <p>Crop this image to 400x300</p>
  <form method="post" action="">
    <input type="hidden" id="x" name="x" />
    <input type="hidden" id="y" name="y" />
    <input type="hidden" id="w" name="w" />
    <input type="hidden" id="h" name="h" />
    <button type="submit"><?php echo image_tag("blacks/16x16/checkmark.png");?> Crop this image</button>
  </form>
</div>
<div class="clear"></div>



<script type="text/javascript">
var jcropApi = null;
var ratio = 400/300;

function showPreview(coords){
  var rx = 400 / coords.w;
  var ry = 300 / coords.h;

  $('#preview').css({
    width: Math.round(rx * <?php echo $imgw; ?>) + 'px',
    height: Math.round(ry * <?php echo $imgh; ?>) + 'px',
    marginLeft: '-' + Math.round(rx * coords.x) + 'px',
    marginTop: '-' + Math.round(ry * coords.y) + 'px'
  });

  $('#x').val(coords.x);
  $('#y').val(coords.y);
  $('#w').val(coords.w);
  $('#h').val(coords.h);
};

function getRandom() {
  var dim = jcropApi.getBounds();
  var x1 = Math.round(Math.random() * (dim[0]-400));
  var y1 = Math.round(Math.random() * (dim[1]-300)); 
  return [
    x1,
    y1,
    x1+400,
    y1+300
  ];
};

$(document).ready(function(){
  
  jcropApi = $.Jcrop('img#crop');
  jcropApi.setOptions({
    onChange: showPreview,
    onSelect: showPreview,
    minSize: [400,300],
    allowSelect: false,
    aspectRatio: ratio
  });

  jcropApi.setSelect(getRandom());
});
</script>