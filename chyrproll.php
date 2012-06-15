<?php

	class ChyrpRoll extends Modules
	{
		static function __install()
		{
			$config = Config::current();
			$config->set('chyrproll_blogs', array("Dean Davidson" => "http://blog.itsdeandavidson.co.uk", "Samuel Littley" => "http://www.toastwaffle.com", "Chyrp" => "http://chyrp.net/blog/"), true);
			$config->set('chyrproll_title', "Blogroll", true);
		}

		static function __uninstall($confirm)
		{
			if($confirm)
			{
				$config = Config::current();
				$config->remove('chyrproll_blogs');
				$config->remove('chyrproll_title');
			}
		}

		public function settings_nav($navs)
		{
			if(Visitor::current()->group->can("change_settings"))
				$navs["chyrproll_settings"] = array("title" => __("Chyrp Roll", "chyrproll"));

			return $navs;
		}

		public function admin_chyrproll_settings($admin)
		{
			$config = Config::current();
			if(empty($_POST)) {
			    $blogs = $config->chyrproll_blogs;
			    $blogtext = "";
			    foreach ($blogs as $link => $url) {
			        $blogtext .= $url.",".$link."\n";
		        }
				return $admin->display("chyrproll_settings", array("blogtext" => $blogtext));
			}

			$lines = explode("\n",$_POST['chyrproll_blogs']);
			$lines = array_filter($lines,'trim');
			$blogs = array();
			foreach ($lines as $line) {
			    $phrases = explode(",",$line);
			    $url = trim(array_shift($phrases));
			    $link = "";
			    while ($phrase = array_shift($phrases)) {
			        $link .= $phrase;
		        }
		        $link = trim($link);
		        $blogs[$link] = $url;
	        }

			if(($config->set("chyrproll_blogs", $blogs)) && ($config->set("chyrproll_title", $_POST['chyrproll_title'])))
				Flash::notice(__("Settings updated."), "/admin/?action=chyrproll_settings");
		}

		public function sidebar()
		{
			$config = Config::current();
			$blogs = $config->chyrproll_blogs;

			if (!empty($blogs))
			{
				echo '                <div id="blogroll"><h1>'.$config->chyrproll_title.'</h1>';
				echo '                <ul id="blogroll_blogs">';

				foreach($blogs as $key => $blog)
				{
					echo '                    <li><a href="' . $blog . '">' . $key . '</a></li>';
				}
				echo '                </ul></div>';
			}
		}
	}

	$chyrproll = new ChyrpRoll();

?>
