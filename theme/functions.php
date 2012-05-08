<?php
	function base_url($url=null)
	{
		return CBapelsin::instance()->request->base_url . trim($url, '/');
	}
	function create_url($url)
	{
		return CBapelsin::instance()->request->createUrl($url);
	}
	function get_debug()
	{
		$bap=CBapelsin::instance();
		$html=null;
		if(isset($bap->config['debug']['db-num-queries']) && $bap->config['debug']['db-num-queries'] && isset($bap->db)) 
		{
			$html .= "<p>Database made " . $bap->db->GetNumQueries() . " queries.</p>";
		}    
		if(isset($bap->config['debug']['db-queries']) && $bap->config['debug']['db-queries'] && isset($bap->db)) 
		{
			$html .= "<p>Database made the following queries.</p><pre>" . implode('<br/><br/>', $bap->db->GetQueries()) . "</pre>";
		}    
		
		if(isset($bap->config['debug']['display-bapelsin']) && $bap->config['debug']['display-bapelsin']) 
		{
			$html = "<hr><h3>Debuginformation</h3><p>The content of CBapelsin:</p><pre>" . htmlent(print_r($bap, true)) . "</pre>";
		} 
		if(isset($bap->config['debug']['timer']) && $bap->config['debug']['timer'])
			$html.="Page was generated in ".round(microtime(true)-$bap->timer['first'],2)." seconds!";
		return $html;
	}
	function login_menu()
	{
		$menu="";
		$bap=CBapelsin::instance();
		
		if($bap->user->isAuthenticated())
		{
			$user=$bap->user->getUserProfile();
			$menu="<a href='http://gravatar.com/site/signup/'><img class='gravatar' src='".get_gravatar(20)."'></a>";
			$menu.="<a href='{$bap->request->createUrl('user/profile')}'>{$user['acronym']}</a> ";
			if($bap->user->isAdministrator())
			{
				$menu.="<a href='{$bap->request->createUrl('acp')}'>acp</a> ";
			}
			$menu.="<a href='{$bap->request->createUrl('user/logout')}'>logout</a>";
		}
		else
		{
			$menu="<a href='{$bap->request->createUrl('user/login')}'>Login</a>";
		}		
		return $menu;
	}
	function render_views($region='default')
	{
		return CBapelsin::instance()->views->render($region);
	}
	function region_has_content($region='default' /*...*/)
	{
		return CBapelsin::instance()->views->regionHasView(func_get_args());
	}
	function get_messages_from_session()
	{
		$messages = CBapelsin::instance()->session->getMessages();
		
		$html = null;
		if(!empty($messages)) 
		{
			foreach($messages as $val) 
			{
				$valid = array('info', 'notice', 'success', 'warning', 'error', 'alert');
				$class = (in_array($val['type'], $valid)) ? $val['type'] : 'info';
				$html .= "<div class='$class'>{$val['message']}</div>\n";
			}
		}
		return $html;
	}
	function theme_url($url) 
	{
		$bap = CBapelsin::instance();
		return "{$bap->request->base_url}theme/{$bap->config['theme']['name']}/{$url}";
	}
	function filter_data($data, $filter)
	{
		return CMContent::filter($data,$filter);
	}
	function get_gravatar($size=null) 
	{
		return 'http://www.gravatar.com/avatar/' . 
			md5(strtolower(trim(CBapelsin::Instance()->user['email']))) . '.jpg?' . 
			($size ? "d=retro&s=$size" : null);
	}
