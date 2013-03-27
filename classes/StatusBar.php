<?php 
class StatusBar{
	private $progress;
	private $width;
	private $style; //info, success, warning,danger
	public function __construct($progress,$style='info', $width = 650){
		$this->set_progress($progress);
		$this->width = $width;
		$this->style = $style;
	}
	public function set_progress($progress){
		if($progress > 100){
			$this->progress = 100;
		}
		elseif($progress<0){
			$this->progress = 0;
		}
		else{
			$this->progress = $progress;
		}
	}
	public function set_width($width){
		$this->width = $width;
	}
	public function show_progress_bar(){
		if($this->progress >= 100){
			$this->style = 'danger';
		}
			echo '<div class="progress progress-'.$this->style.' progress-striped" style=" width: '.$this->width.'px;">
			<div class="bar" style="width: '.$this->progress.'%"></div></div>';
	}
}

?>