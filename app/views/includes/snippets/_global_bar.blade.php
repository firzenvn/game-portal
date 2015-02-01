<nav class="navbar navbar-default" role="navigation" style="margin-bottom:0px;">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#menu-head-responsive">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="#">{{HTML::image('images/logo-small.png')}}</a>
		</div>
		<div class="collapse navbar-collapse" id="menu-head-responsive">
			<ul class="nav navbar-nav navbar-left">
				<?php $menus = Menu::where('active', '=', 1)->orderBy('id', 'asc')->orderBy('order_number', 'asc')->get(); ?>
				@if(!empty($menus))
				@foreach($menus as $key=>$menu)
				@if(!$menu->parent_id)
				<?php unset($menus[$key]); ?>
				<li class="dropdown">
					<a class="dropdown-toggle"  href="{{$menu->uri}}"><i
							class=" fa {{$menu->class}}"></i>{{$menu->name}}
						@if(!genSubMenu($menus,$menu->id,0))
					</a>
					@endif
				</li>
				@endif
				@endforeach
				@endif

				<?php
				/**
				 * @param array $menus
				 * @param int $parent_id
				 * @param int $level
				 * Tạo ra menu con
				 * @author CaoPV
				 */
				function genSubMenu(&$menus = array(), $parent_id = 0, $level = 0)
				{
					$level += 1;
					if (!empty($menus)) {
						$cnt = 0;
						foreach ($menus as $key => $menu) {
							if ($menu->parent_id == $parent_id) {
								$cnt++;
								if ($cnt == 1) {
									echo '<b class="caret"></b></a>';
									echo '<ul class="dropdown-menu">';
								}
								if ($level != 0) {
									echo '<li class="submenu"><a href="' . $menu->uri . '"><i class="fa ' . $menu->class . '"></i> ' . $menu->name;
								} else {
									echo '<li><a href="' . $menu->uri . '"><i class="fa ' . $menu->class . '"></i> ' . $menu->name;
								}
								if (!genSubMenu($menus, $menu->id, $level))
									echo '</a>';
								echo '</li>';
								echo '<li role="presentation" class="divider"></li>';
								unset($menus[$key]);
							}
						}
						if ($cnt) {
							echo '</ul>';
							return true;
						} else {
							return false;
						}
					}
					return false;
				}

				?>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li>
					<div class="auth">
						<div class="btn-group">
							<label>
								@if (Auth::check())
								{{ Auth::user()->name }}
								{{ Auth::user()->email }}
								@endif
								<span data-toggle="dropdown" class="glyphicon glyphicon-align-justify _icon-auth"></span>
								<ul class="dropdown-menu navbar-right" role="menu">
									<li>
										{{ HTML::decode(HTML::link('users/logout','<span
											class="glyphicon glyphicon-off text-error"></span> Thoát')) }}
									</li>
									<li>
										{{ HTML::decode(HTML::link('users/new-password','<span
											class="glyphicon glyphicon-refresh"></span> Đổi mật khẩu')) }}
									</li>
								</ul>
								<div class="clearfix"></div>
							</label>
						</div>
					</div>
				</li>
			</ul>
		</div>
	</div>
</nav>