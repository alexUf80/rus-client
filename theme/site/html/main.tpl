{capture name='page_scripts'}

<script src="theme/site/js/calc.app.js?v=1.05"></script>
<script src="theme/site/js/main.app.js?v=1.27"></script>

{/capture}

{capture name='page_styles'}

{/capture}

{if $bankiru}

{/if}

{include file='calculator.tpl'}

{include file='misc.tpl'}

{include file='faq.tpl'}
