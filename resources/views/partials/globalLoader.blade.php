<div id="global-loading-root"></div>
@if(!isset($skipAppJs) || !$skipAppJs)
  @vite(['resources/js/app.js'])
@endif


















