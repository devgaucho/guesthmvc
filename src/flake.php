<?php
namespace gaucho;
use gaucho\model;

class flake extends model{
	var $sequenceNumberLength;
	var $unixTimeLength;
	function __construct($sequenceNumberLength=3){
		$this->sequenceNumberLength=$sequenceNumberLength;
		$this->unixTimeLength=6;
	}
	function base($number,$alphabet){
		$base=strlen($alphabet);
		$result='';
		if($number==0){
			return $alphabet[0]; // Caso especial para zero
		}
		while($number>0){
			$remainder=$number%$base;
			$result=$alphabet[$remainder].$result;
			$number=(int)($number/$base);
		}
		return $result;
	}
	function decode($flake){
		// unixTime
		$unixTime=substr($flake,0,$this->unixTimeLength);
		$unixTime=base_convert($unixTime,36,10);

		// machineId
		$machineId=substr($flake,$this->unixTimeLength,2);
		$machineId=base_convert($machineId,36,10);

		// sequenceNumber
		$sequenceNumber=substr($flake,8,$this->sequenceNumberLength);
		$sequenceNumber=base_convert($sequenceNumber,36,10);

		return [
			'unixTime'=>$unixTime,		
			'machineId'=>$machineId,
			'sequenceNumber'=>$sequenceNumber,
		];
	}
	function encode($unixTime,$machineId,$sequenceNumber){
		$base36='0123456789';
		$base36.=strtolower('ABCDEFGHIJKLMNOPQRSTUVWXYZ');
		$alphabet=$base36;

		// unix
		$unixTime=$this->base($unixTime,$alphabet);
		$unixTime=$this->zeros($unixTime,6);

		// machineId
		$machineId=$this->base($machineId,$alphabet);
		$machineId=$this->zeros(
			$machineId,
			2
		);

		// sequenceNumber
		$sequenceNumber=$this->base(
			$sequenceNumber,
			$alphabet
		);
		$sequenceNumber=$this->zeros(
			$sequenceNumber,
			$this->sequenceNumberLength
		);

		// flake=unix | machineId | sequenceNumber
		$flake=$unixTime.$machineId.$sequenceNumber;

		// return
		return $flake;
	}
	function getSequenceNumber($messageId,$tableName,$unixTime){
		$where=[
			'created_at'=>$unixTime,
			'id[<=]'=>$messageId
		];
		return $this->db()->count($tableName,$where);
	}
	function zeros($str,$number){
		$len=strlen($str);
		$i=$len;
		while($i<$number){
			$str='0'.$str;
			$i++;
		}
		return $str;
	}
}