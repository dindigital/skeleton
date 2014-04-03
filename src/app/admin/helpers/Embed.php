<?php

namespace src\app\admin\helpers;

class Embed
{

  public static function issuu ( $document_id, $w, $h )
  {
    return '<object style="width:' . $w . 'px;height:' . $h . 'px" ><param name="movie" value="http://static.issuu.com/webembed/viewers/style1/v2/IssuuReader.swf?mode=mini&amp;backgroundColor=%23222222&amp;documentId=' . $document_id . '" /><param name="allowfullscreen" value="true"/><param name="menu" value="false"/><param name="wmode" value="transparent"/><embed src="http://static.issuu.com/webembed/viewers/style1/v2/IssuuReader.swf" type="application/x-shockwave-flash" allowfullscreen="true" menu="false" wmode="transparent" style="width:' . $w . 'px;height:' . $h . 'px" flashvars="mode=mini&amp;backgroundColor=%23222222&amp;documentId=' . $document_id . '" /></object>';
  }

}
