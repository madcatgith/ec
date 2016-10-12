<div class="appeal">
	<h4 class="innerTitle">Форум</h4>
	<table class="phpbbtable">
		{foreach $topics as $post name=count}
			<tr class="tr{$smarty.foreach.count.index}">
				<td class="col1">
					<a href="/forum/viewtopic.php?f={$post.forum_id}&t={$post.topic_id}&p={$post.topic_last_post_id}#p{$post.topic_last_post_id}">{$post.topic_title }</a>
				</td class="col2">
				<td>{$post.topic_first_poster_name}</td>
				<td class="col3">
					<div>{$post.topic_replies_real} Ответов</div>
					{$post.topic_views} Просмотров
				</td>
				<td class="col4">
					<a href="/forum/viewtopic.php?f={$post.forum_id}&t={$post.topic_id}&p={$post.topic_last_post_id}#p{$post.topic_last_post_id}">{$post.topic_last_post_time}</a>
					Последний пост : {$post.topic_last_poster_name}
				</td>
			</tr>
		{/foreach}
	</table>
	<a class="forumLink" href="/forum/">Перейти в форум</a>
</div>