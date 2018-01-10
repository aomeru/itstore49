<?php

?>
<div id="left" class="fixed">

	<div class="menu_scroll left_scrolled">

		<p class="padding-15 c-ccc">
			Welcome {{Auth::user()->firstname}}
			{{-- <br> <span class="font-12x c-999">{{Auth::user()->email}}</span> --}}
		</p>

		<ul id="menu">
			<li class="@if(!isset($nav)) active @endif">
				<a href="{{route('admin.dashboard')}}">
					<i class="fa fa-home fa-fw"></i>
					<span class="link-title menu_hide">&nbsp;Dashboard</span>
				</a>
			</li>

			<?php
			if(Session::has('allowed_pages'))
			{
				foreach(session('allowed_pages') as $page)
				{
					if(count($page['subpages']) > 0)
					{
						echo '<li class="dropdown_menu';
						$nav_array = array();
						foreach($page['subpages'] as $sp)
						{
							array_push($nav_array, $sp['slug']);
						}
						if(isset($nav) && in_array($nav, $nav_array)) echo ' active';

						echo'">
								<a href="javascript:;">
								<i class="fa fa-'.$page['icon'].' fa-fw"></i>
								<span class="link-title menu_hide">&nbsp;'.$page['title'];
								if($page['title'] == 'Support'){
									echo '<span class="ml10 badge bgc-f30">';
										if(isset($unreadSupport)){
											echo $unreadSupport;
										}else{
											echo '0';
										}
									echo '</span>';
								}

								if($page['title'] == 'Private Mail'){
									echo '<span class="ml10 badge bgc-f30">';
										if(isset($unreadGuestMails)){
											echo $unreadGuestMails;
										}else{
											echo '0';
										}
									echo '</span>';
								}

								echo'</span>
								<span class="fa arrow menu_hide"></span>
								</a>
								<ul>';

						foreach($page['subpages'] as $subpage)
						{
							echo '<li class="'; if(isset($nav) && ($nav == $subpage['slug'])) echo 'active'; echo '">
									<a href="'.url('admin/'.$subpage['slug']).'" title="'.$subpage['title'].' page">
									<i class="fa fa-'.$subpage['icon'].' fa-fw"></i>
									<span class="link-title menu_hide">&nbsp;'.$subpage['title'];
									if($subpage['title'] == 'Pending Tickets'){
										echo '<span class="ml10 badge bgc-f30">';
											if(isset($unreadSupport)){
												echo $unreadSupport;
											}else{
												echo '0';
											}
										echo '</span>';
									}
									if($subpage['title'] == 'Received Mail'){
										echo '<span class="ml10 badge bgc-f30">';
											if(isset($unreadGuestMails)){
												echo $unreadGuestMails;
											}else{
												echo '0';
											}
										echo '</span>';
									}
									echo '</span>
									</a></li>';
						}

						echo '</ul></li>';
					} else {
						echo '<li ';
						if(isset($nav) && ($nav == $page['slug'])) echo 'class="active"';
						echo '><a href="'.url('admin/'.$page['slug']).'"><i class="fa fa-'.$page['icon'].' fa-fw"></i><span class="link-title menu_hide">&nbsp;'.$page['title'].'</span></a></li>';
					}
				}
			}
			?>
			{{--
			<li class="dropdown_menu">
				<a href="javascript:;">
					<i class="fa fa-anchor"></i>
					<span class="link-title menu_hide">&nbsp; My Packages</span>
					<span class="fa arrow menu_hide"></span>
				</a>
				<ul>
					<li class="active">
						<a href="general_components">
							<i class="fa fa-angle-right"></i> &nbsp; General Components
						</a>
					</li>
					<li class="active">
						<a href="cards">
							<i class="fa fa-angle-right"></i>
							<span class="link-title">&nbsp;Cards</span>
						</a>
					</li>
					<li>
						<a href="transitions">
							<i class="fa fa-angle-right"></i> &nbsp; Transitions
						</a>
					</li>
					<li>
						<a href="buttons">
							<i class="fa fa-angle-right"></i> &nbsp; Buttons
						</a>
					</li>
					<li>
						<a href="icons">
							<i class="fa fa-angle-right"></i> &nbsp; Icons
						</a>
					</li>
					<li>
						<a href="tooltips">
							<i class="fa fa-angle-right"></i> &nbsp; Tooltips
						</a>
					</li>
					<li>
						<a href="animations">
							<i class="fa fa-angle-right"></i> &nbsp; Animations
						</a>
					</li>

					<li>
						<a href="sliders">
							<i class="fa fa-angle-right"></i> &nbsp; Sliders
						</a>
					</li>
					<li>
						<a href="notifications">
							<i class="fa fa-angle-right"></i> &nbsp; Notifications
						</a>
					</li>
					<li>
						<a href="p_notify">
							<i class="fa fa-angle-right"></i> &nbsp; P-Notify
						</a>
					</li>
					<li>
						<a href="izitoast">
							<i class="fa fa-angle-right"></i> &nbsp; Izi-Toast
						</a>
					</li>
					<li>
						<a href="cropper">
							<i class="fa fa-angle-right"></i> &nbsp; Cropper
						</a>
					</li>
					<li>
						<a href="jstree">
							<i class="fa fa-angle-right"></i> &nbsp;Js Tree
						</a>
					</li>
					<li>
						<a href="modal">
							<i class="fa fa-angle-right"></i> &nbsp; Modals
						</a>
					</li>


					<li>
						<a href="sortable">
							<i class="fa fa-angle-right"></i> &nbsp; Sortable
						</a>
					</li>
					<li>
						<a href="sweet_alert">
							<i class="fa fa-angle-right"></i> &nbsp; Sweet alerts
						</a>
					</li>
					<li>
						<a href="grids_layout">
							<i class="fa fa-angle-right"></i> &nbsp; Grid View
						</a>
					</li>
				</ul>
			</li>
			--}}

            <li>
                <a href="{{route('admin.logout')}}">
                    <i class="fa fa-sign-out fa-fw"></i>
                    <span class="link-title menu_hide">&nbsp;Log Out</span>
                </a>
            </li>
		</ul>

	</div>

</div>
