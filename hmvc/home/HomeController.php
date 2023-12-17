<?php
namespace hmvc\home;
use hmvc\messages\MessagesModel;
use gaucho\view;
use gaucho\controller;
class HomeController extends controller{
	function GET(){
		$MessagesModel=new MessagesModel();
		$messages=$MessagesModel->readAll();
		$data=[
			'title'=>$_ENV['SITE_NAME'],
			'messages'=>$this->loopDaMensagens($messages)
		];
		$this->view('home/head',$data);
		$this->view('home/read',$data);
	}
	function loopDaMensagens($messages){
		$str=null;
		foreach ($messages as $message) {
			$data=[
				'message'=>$message
			];
			$str.=$this->view(
				'messages/loop',$data,false
			);
		}
		return $str;
	}
}
