<?php

class search_engine {

	/* 这是一个类，理解类是PHP学习中的一个重要步骤，查阅相关资料可以加深对它的理解 */

	public static $author_info=array(
		"name"=>"John Zhang",
		"email"=>"johnzhang@1cf.co",
		"QQ"=>"2793203840",
		"blog"=>"https://www.johnzhang.xyz"
	);

	/* 请注意 public/private/protected 和 static */

	public $searchEngineName='';
	public $searchEnginelink='';
	public $q;
	public $page;
	public $citeDOM;
	public $titleDOM;
	public $descDOM;
	public $start;
	
	function Search() {
		$result=array();
		$url=$this->searchEnginelink."".$this->q."&".$this->start."=".($this->page);
		//echo $url;
		$html_inner = file_get_html($url);
		foreach($html_inner->find($this->citeDOM) as $ece){
			/* 对于同一种结果，通常有多种实现方法，这里列举了两种
			$temp=array(
				"cite"=>$ece->innertext
			);
			$result[]=$temp;
			*/
			array_push($result,array(
					"cite"=>$ece->innertext
				)
			);
		}
		$j=0;
		foreach($html_inner->find($this->titleDOM) as $ece){
			$result[$j]["link"]=($this->searchEngineName=="Baidu"?($ece->find('a', 0)->href):($ece->href));
			if(searchEngineName=="Baidu") $result[$j]["secure"]=false;
			else if(substr($result[$j]["link"],0,8)=="https://") $result[$j]["secure"]=true;
			else $result[$j]["secure"]=false;
			$result[$j]["title"]=($this->searchEngineName=="Baidu"?($ece->find('a', 0)->innertext):($ece->innertext));
			$j++;
		}
		$j=0;
		foreach($html_inner->find($this->descDOM) as $ece){
			$result[$j]["desc"]=$ece->innertext;
			$j++;
		}
		return $result;
	}
 }



?>
