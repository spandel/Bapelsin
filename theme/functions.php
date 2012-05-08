<?php
	function base_url($url=null)
	{
		return CLydia::instance()->request->base_url . trim($url, '/');
	}
	function create_url($url)
	{
		return CLydia::instance()->request->createUrl($url);
	}
	function get_debug()
	{
		$ly=CLydia::instance();
		$html=null;
		if(isset($ly->config['debug']['db-num-queries']) && $ly->config['debug']['db-num-queries'] && isset($ly->db)) 
		{
			$html .= "<p>Database made " . $ly->db->GetNumQueries() . " queries.</p>";
		}    
		if(isset($ly->config['debug']['db-queries']) && $ly->config['debug']['db-queries'] && isset($ly->db)) 
		{
			$html .= "<p>Database made the following queries.</p><pre>" . implode('<br/><br/>', $ly->db->GetQueries()) . "</pre>";
		}    
		
		if(isset($ly->config['debug']['display-lydia']) && $ly->config['debug']['display-lydia']) 
		{
			$html = "<hr><h3>Debuginformation</h3><p>The content of CLydia:</p><pre>" . htmlent(print_r($ly, true)) . "</pre>";
		} 
		if(isset($ly->config['debug']['timer']) && $ly->config['debug']['timer'])
			$html.="Page was generated in ".round(microtime(true)-$ly->timer['first'],2)." seconds!";
		return $html;
	}
	function login_menu()
	{
		$menu="";
		$ly=CLydia::instance();
		
		if($ly->user->isAuthenticated())
		{
			$user=$ly->user->getUserProfile();
			$menu="<a href='http://gravatar.com/site/signup/'><img class='gravatar' src='".get_gravatar(20)."'></a>";
			$menu.="<a href='{$ly->request->createUrl('user/profile')}'>{$user['acronym']}</a> ";
			if($ly->user->isAdministrator())
			{
				$menu.="<a href='{$ly->request->createUrl('acp')}'>acp</a> ";
			}
			$menu.="<a href='{$ly->request->createUrl('user/logout')}'>logout</a>";
		}
		else
		{
			$menu="<a href='{$ly->request->createUrl('user/login')}'>Login</a>";
		}		
		return $menu;
	}
	function render_views($region='default')
	{
		return CLydia::instance()->views->render($region);
	}
	function region_has_content($region='default' /*...*/)
	{
		return CLydia::instance()->views->regionHasView(func_get_args());
	}
	function get_messages_from_session()
	{
		$messages = CLydia::instance()->session->getMessages();
		
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
		$ly = CLydia::instance();
		return "{$ly->request->base_url}theme/{$ly->config['theme']['name']}/{$url}";
	}
	function filter_data($data, $filter)
	{
		return CMContent::filter($data,$filter);
	}
	function get_gravatar($size=null) 
	{
		return 'http://www.gravatar.com/avatar/' . 
			md5(strtolower(trim(CLydia::Instance()->user['email']))) . '.jpg?' . 
			($size ? "d=retro&s=$size" : null);
	}
