    <div id="debuger" class="appDebuger">
    {math equation="b / 1024" b=$app->memoryUsage assign=kb}
    {math equation="kb / 1024" kb=$kb assign=mb}
    	<table>
    		<tr><td>Application Init Time:</td><td>{$app->appInitTime}</td></tr>
    		<tr><td>Application Run Time:</td><td>{$app->appRunTime}</td></tr>
    		<tr><td>Application Shutdwon Time:</td><td>{$app->appShutdownTime}</td></tr>
    		<tr><td>Application Total Exec Time:</td><td>{$app->globalAppTime}</td></tr>
    		<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
    		<tr><td>MySQL Queries Count:</td><td>{$app->sqlQueriesCnt}</td></tr>
    		<tr><td>MySQL Queries Run time:</td><td>{$app->sqlRunTime}</td></tr>
    		<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
    		<tr><td>Memory Usage:</td><td>{$app->memoryUsage} Bytes / {$kb} Kb / {$mb} Mb)</td></tr>
    	</table>
    </div>