{foreach $options['row_seq'] as $key}
  	{if array_key_exists($key, $options['nonlang_field']) eq true}
    	{$value = $options['nonlang_field'][$key]}
    {elseif array_key_exists($key, $options['multylang_field']) eq true}
        {$value = $options['multylang_field'][$key]}
    {else}
        {continue}
    {/if}
{/foreach}