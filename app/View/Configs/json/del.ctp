<?php echo json_encode(array('statusCode'=>$statusCode,'message'=>$this->Session->flash('flash',array('element'=>'text')),'callbackType'=>'closeCurrent','navTabId'=>'main'))?>