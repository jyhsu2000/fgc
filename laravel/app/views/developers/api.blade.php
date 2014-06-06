@extends('layouts.default')

@section('title')
    API - 開發人員
@stop

@section('content')
<div class="container text-left">
<div class="alert alert-error">
    <h3>注意：</h3>
    Web API使用協定除原有<code>https</code>之外，現已支援<code>http</code>，自行更換網址前綴即可<br />
    <br />
    Web API的<code>getToken</code>小幅修改，現在<code>gameID</code>改為強制附上<br />
    進行登入驗證時，請附上<code>gameID</code>，會一併檢查玩家是否有該遊戲角色ID，並且會將ID回傳<br />
    若沒附上<code>gameID</code>，將會得到錯誤訊息<code>Require gameID</code><br />
    詳情請見下方API區段
</div>
<div class="alert alert-block">
    <h3>API 補充說明：</h3>
    有個問題似乎在API上沒有詳細說明OTL<br />
    如果你的遊戲 client 登入後 如果目前你的遊戲 只有你在線上的話<br />
    我的 list 資料會設定為<code>null</code><br />
    然後間隔一定時間（目前設定<code>3秒</code>，可能更改）<br />
    會<code>再次重新傳送 list JSON</code>給client<br />
    直到 list 上有人才會停止間隔傳送<br />
    請注意 第二次以後傳送的 list資料還是有可能為 <code>null</code> !!!!!<br />
</div>
<div class="alert alert-info">
    <h3>通知：</h3>
    請各組告訴我你們遊戲要使用的gameID<br />
    （可以在課堂在告訴我, 或是mail <a href="mailto:d0078154@fcu.edu.tw" target="_blank">d0078154@fcu.edu.tw</a>）<br />
    （所有圖片可以右鍵下載）<br />
    <br />
    使用Java開發遊戲的開發者可使用此函式庫來建構JSON String <a href="https://github.com/douglascrockford/JSON-java" target="_blank">下載</a> <a href="http://www.json.org/java" target="_blank">說明文件</a><br />
   <h3>(更新！）範例程式碼新增了Socket與API的例子</h3> <a href="https://github.com/gnehcmit/fgc_tools" target="_blank">code example</a><br />
    <h3 style="color:#0000FF">請大家注意JSON格式有大小寫之分！(case sensitive)</h3><br />
    <h2 style="color:#FF0000">stub server有更新！ 請趕快升級<br />有什麼問題可以課堂上詢問 <a href="https://github.com/gnehcmit/fgc_backend/releases/tag/0.2-alpha" target="_blank">連結</a></h2>
</div>

FGC後端遊戲通訊流程圖<br />
<img src="/resource/pic/developers/api/Main_Diagram.png" />
<h2>API</h2>
<h3>◆刷新並取得Token</h3>
<a href="https://fgc.heapthings.com/api/getToken" target="_blank">https://fgc.heapthings.com/api/getToken</a><br />
輸入：username、password、gameID<br />
　　成功回傳：result=&gt;true、username、gameID、id、token、tokenDeadline<br />
　　失敗回傳：result=&gt;false、error=&gt;(Error Message)<br />
<br />
<table class="table table-bordered">
    <tbody>
        <tr>
            <td>輸入</td>
            <td>成功回傳</td>
            <td>失敗回傳</td>
        </tr>
        <tr>
            <td>
                username<br />
                password<br />
                gameID
            </td>
            <td>
                result =&gt; true<br />
                username<br />
                gameID<br />
                id<br />
                token<br />
                tokenDeadline<br />
            </td>
            <td>
                result =&gt; false<br />
                error =&gt; (Error Message)
            </td>
        </tr>
    </tbody>
</table>
Error Message：<br />
　　◇Invalid Request（未輸入帳號或密碼）<br />
　　◇Login Failed（帳號或密碼錯誤）<br />
　　◇Email Unverified（尚未通過Email驗證）<br />
　　◇Invalid gameID（遊戲不存在）<br />
　　◇No ID（該帳號沒有該遊戲的ID）<br />
　　◇Require gameID（未指定遊戲ID）<br />
<br />
<h3>◆檢查Token</h3>
<a href="https://fgc.heapthings.com/api/checkToken" target="_blank">https://fgc.heapthings.com/api/checkToken</a><br />
輸入：username、token<br />
　　成功回傳：result=&gt;true、username、token<br />
　　失敗回傳：result=&gt;false、error=&gt;(Error Message)<br />
<br />
<table class="table table-bordered">
    <tbody>
        <tr>
            <td>輸入</td>
            <td>成功回傳</td>
            <td>失敗回傳</td>
        </tr>
        <tr>
            <td>
                username<br />
                token
            </td>
            <td>
                result =&gt; true<br />
                username<br />
                token
            </td>
            <td>
                result =&gt; false<br />
                error =&gt; (Error Message)<br />
            </td>
        </tr>
    </tbody>
</table>
Error Message：<br />
　　◇Invalid Request（未輸入帳號或Token）<br />
　　◇Invalid username（帳號不存在，或非本地帳號）<br />
　　◇Invalid token（Token錯誤）<br />
　　◇Token expired（Token過期）<br />
<br />
<h3>◆取得角色ID</h3>
<a href="https://fgc.heapthings.com/api/getID" target="_blank">https://fgc.heapthings.com/api/getID</a><br />
輸入：username、gameID<br />
　　成功回傳：result=&gt;true、username、gameID、id<br />
　　失敗回傳：result=&gt;false、error=&gt;(Error Message)<br />
<br />
<table class="table table-bordered">
    <tbody>
        <tr>
            <td>輸入</td>
            <td>成功回傳</td>
            <td>失敗回傳</td>
        </tr>
        <tr>
            <td>
                username<br />
                gameID
            </td>
            <td>
                result =&gt; true<br />
                username<br />
                gameID<br />
                id
            </td>
            <td>
                result =&gt; false<br />
                error =&gt; (Error Message)<br />
            </td>
        </tr>
    </tbody>
</table>
Error Message：<br />
　　◇Invalid Request（未輸入帳號或gameID）<br />
　　◇Invalid username（帳號不存在，或非本地帳號）<br />
　　◇Invalid gameID（遊戲不存在）<br />
　　◇No ID（該帳號沒有該遊戲的ID）<br />
<br />
<h2>Socket 連線資訊</h2>
Address：<a href="http://fgc.heapthings.com" target="_blank">fgc.heapthings.com</a><br />
Port：5566<br />
請使用UTF-8編碼傳送JSON資料<br />
<br />
<h2>JSON傳輸格式</h2>
（前方標籤為JSON的發送方）<br />
<br />
<span class="label label-info">Client</span> 連線後身份驗證
<pre>
{
     "token": (string),
     "gameID": (string)
}
</pre>
<br />
<span class="label label-important">Server</span> 身份驗證失敗
<pre>
{
     "result": false (boolean)
}
</pre>
<br />
<span class="label label-important">Server</span> 身份驗證成功後傳送的玩家列表
<pre>
{
    "result": true (boolean),
    "list": [
        {"id": (string)},
        {"id": (string)},
        ...
        {"id": (string)}
    ]
}
</pre>
<br />
<span class="label label-info">Client</span> 要求與誰對戰之請求
<pre>
{
    "invite": id(string)
}
</pre>
<br />
<span class="label label-important">Server</span> 伺服器配對結果 resultID 0
<pre>
{
    "resultID": 0(int)
}
</pre>
<br />
<span class="label label-important">Server</span> 伺服器配對結果 resultID 1, 附上對方玩家id
<pre>
{
    "resultID": 1(int),
    "id": (string)
}
</pre>
<br />
<span class="label label-info">Client</span> 回覆是否接受對方對戰要求
<pre>
{
    "accept": (boolean)
}
</pre>
<br />
<span class="label label-important">Server</span> 伺服器配對結果 resultID 2, 附上玩家列表
<pre>
{
    "resultID": 2(int)
    "list": [
        {"id": (string)},
        {"id": (string)},
        ...
        {"id": (string)}
    ]
}
</pre>
<br />
<span class="label label-important">Server</span> 遊戲開始, 對方玩家id與誰先誰後
<pre>
{
    "id": (string),
    "whoFirst": boolean
}
</pre>
<br />
<span class="label label-info">Client</span> 棋步資料與是否寫入資料庫的註記
<pre>
{
    "data": (string),
    "PutItThere": (boolean)
}
</pre>
<br />
<span class="label label-info">Client</span> 遊戲結束 獲勝者的id
<pre>
{
    "winner": 獲勝者id (id)(string)
}
</pre>
(補充, 當有一玩家送出了winner資訊後, 如果server在30秒內沒有收到另一方winner資訊, 將會自動斷開對方的Socket連線)<br />
<br />
<br />
<span class="label label-important">Server</span> 伺服器回報玩家收到獲勝者id資訊
<pre>
{
    "result": true(boolean)
}
</pre>
<br />
<br />
<h2>各狀態的Sequence Diagram</h2>
<br />
<b>登入成功：</b><br />
<img src="/resource/pic/developers/api/LoginSuccess.png" /><br />
<b>登入失敗：</b><br />
<img src="/resource/pic/developers/api/LoginFail.png" /><br />
<b>等候時間過長：</b><br />
<img src="/resource/pic/developers/api/MatchTooLong.png" /><br />
<b>玩家配對 result0：</b><br />
<img src="/resource/pic/developers/api/MatchCase0.png" /><br />
<b>玩家配對 result1(成功)：</b><br />
<img src="/resource/pic/developers/api/MatchCase1_acecpt.png" /><br />
<b>玩家配對 result1(拒絕)：</b><br />
<img src="/resource/pic/developers/api/MatchCase1_reject.png" /><br />
<b>玩家配對 result2：</b><br />
<img src="/resource/pic/developers/api/MatchCase2.png" /><br />
<b>玩家對戰時：</b><br />
<img src="/resource/pic/developers/api/Playing.png" />
</div>



@stop


