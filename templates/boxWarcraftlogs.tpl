{if $warcraftlogs && $warcraftlogs|count > 0}
	<ul class="sidebarItemList">
	{foreach from=$warcraftlogs key=index item=wclog}
			<li class="box24">
			<a href="{$wclog['link']}">
			<img class="userAvatarImage" src="{$wclog['icon']}" width="48" height="48" />
			</a>			
			<div class="sidebarItemTitle">
				<h3><a href="{$wclog['link']}">{$wclog['title']}</a></h3>
													<small>
													{@$wclog['start']|time}
													</small>
							</div>
		</li>
	{/foreach}		
</ul>
{else}
	<p class="error">{lang}eu.eqdkp-plus.wsc.warcraftlogs.boxError{/lang}</p>
{/if}