<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width">
  <title>MOOV</title>
  <link href="https://fonts.googleapis.com/css?family=Material+Icons|Noto+Sans" rel="stylesheet">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <style>
    @import url(//fonts.googleapis.com/earlyaccess/notosanstc.css);
    body {
      font-family: 'Noto Sans', 'Noto Sans TC', sans-serif;
      font-size: 18px;
      padding-top: 10px;
      padding-bottom: 10px;
    }
    input {
      height: 38px;
      vertical-align: middle;
      border: 1px solid rgba(0,0,0,.15);
      border-radius: .25rem;
      padding: .5rem .75rem;
      border-bottom-right-radius: 0;
      border-top-right-radius: 0;
    }
    #search_box, #total_result {
      padding-top: 16px;
    }
    #_search {
      border-bottom-left-radius: 0;
      border-top-left-radius: 0;
      margin-left: -5.5px;
    }
    img {
      width: 64px;
    }
    .material-icons, .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
      vertical-align: middle;
    }
    #result {
      padding-top: 10px;
    }
  </style>
</head>
<body>
<div class="container-fluid">
<div id="search_box">
<input type="text" id="search" placeholder="歌手或歌曲名稱">
<a id="_search" class="btn btn-default" href="#" role="button"><i class="material-icons">search</i>搜尋</a>
</div>
<div id="total_result"></div>
<div id="function">
<a id="_playlist" class="btn btn-default" href="#" role="button"><i class="material-icons">playlist_play</i>下載播放清單</a>
<a id="_downloadlist" class="btn btn-default" href="#" role="button"><i class="material-icons">get_app</i>下載歌曲</a>
</div>
<div id="result"></div>
<div id="load_more"><button id="_more" class="btn btn-default btn-lg btn-block" type="submit"><i class="material-icons">expand_more</i>載入更多</button></div>
<textarea id="playlist" class="form-control d-none" rows="10"></textarea>
<textarea id="downloadlist" class="form-control d-none" rows="10"></textarea>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.1/umd/popper.min.js" integrity="sha256-AoNQZpaRmemSTxMy9xcqXX5VLwI6IMPYugO7bFHOW+U=" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script>
  $("#function").hide();
  $("#_playlist").hide();
  $("#_downloadlist").hide();
  $("#load_more").hide();
  uuid = "92329D39-6F5C-4520-ABFC-AAB64544E172"; // can random
  proxy = "https://moov-proxy.herokuapp.com/"; // CORS proxy
  playlist = "#EXTM3U\r";
  downloadlist = "@echo off\r\n";
  downloadlist += "chcp 65001\r\n";
  $search = function(from) {
    from = from || 0;
    result_from = from;
    result_count = 20; // number of results to display per request
    //search_api = "https://mtg.now.com/moov/api/search/search?type=&from=" + result_from + "&count=" + result_count + "&value=" + $("#search").val();
    search_api = "https://search-hk.moov-music.com/search/api/search/search?type=&from=" + result_from + "&count=" + result_count + "&value=" + $("#search").val();
    playlist_table_s = "<div class=\"table-responsive\"><table class=\"table table-hover\"><thead><tr><th></th><th>歌手</th><th>歌曲</th><th>專輯</th><th></th><th></th><th></th></tr></thead><tbody>";
    playlist_table_b = "";
    playlist_table_e = "</tbody></table></div>";
    $.get(proxy + search_api, function(data) {
      $("#total_result").empty().append("<p class=\"text-muted\">歌曲: " + data.dataObject.products.total + " 項搜尋結果</p>");
      if(data.dataObject.products.total / result_count > 1 && result_from < data.dataObject.products.total - result_count) {
        $("#load_more").show();
      } else {
        $("#load_more").hide();
      }
      result_from = result_from + result_count;
      $.each(data.dataObject.products.primarySearch, function(index, value) {
        name = value.artist + " - " + value.productTitle;
        url = "https://eservice-hk.net/moov/api.php?uuid=" + uuid + "&pid=" + value.productId;
        playlist_table_b += "<tr><th scope=\"row\"><img src=\"" + value.image + "\"></th><td>" + value.artist + "</td><td>" + value.productTitle + "</td><td>" + value.albumTitle + "</td><td><a href=\"" + url + "\" title=\"播放\"><i class=\"material-icons\">play_circle_outline</i></a></td><td><a href=\"#\" title=\"加入播放清單\" onclick=\"$addplaylist(\'" + name + "\', \'" + encodeURIComponent(url) + "\');this.removeAttribute('href');this.removeAttribute('onclick');return false;\"><i class=\"material-icons\">playlist_add</i></a></td><td><a href=\"#\" title=\"加入下載列表\" onclick=\"$adddownloadlist(\'" + name + "\', \'" + encodeURIComponent(url) + "\');this.removeAttribute('href');this.removeAttribute('onclick');return false;\"><i class=\"material-icons\">queue</i></a></td></tr>";
      });
      if(result_from <= result_count) {
        $("#result").append(playlist_table_s + playlist_table_b + playlist_table_e);
      } else {
        $(".table>tbody").append(playlist_table_b);
      }
    }).fail(function() {
      alert("系統繁忙，請稍後再試。");
    });
    if($("#playlist").html() == "" && $("#downloadlist").html() == "") {
      $("#function").hide();
    } else {
      $("#function").show();
    }
  }
  //https://mtg.now.com/moov/api/content/getProductDetail?productId=VAEG01978850
  $get_detail = function() {

  }
  //https://mtg.now.com/moov/api/content/checkout?deviceid=28861520-144C-4F9A-9B55-8AF1554C4A3A&cat=user&refid=user&reftype=user&pid=VAEG01403476&preview=F&devicetype=iPhone&connect=Phone&quality=LL&application=moovnext&clientver=2.3.3
  $addplaylist = function(title, url) {
    playlist += "#EXTINF:0," + title + "\r";
    playlist += decodeURIComponent(url) + "\r";
    $("#playlist").html(playlist);
    $("#function").show();
    $("#_playlist").show();
  }
  $adddownloadlist = function(title, url) {
    //alert("開發中...");
    downloadlist += "ffmpeg -i \"" + decodeURIComponent(url) + "\" -c copy \"" + title + ".ts\"\r\n";;
    $("#downloadlist").html(downloadlist);
    $("#function").show();
    $("#_downloadlist").show();
  }
  //New Plugs 新歌
  //https://mtg.now.com/moov/api/profile/getProfileList?refType=PPC&categoryId=PPC1000000087&deviceType=web&page=1&_=1481553927446
  //Local New Plugs 本地新歌
  //https://mtg.now.com/moov/api/profile/getProfile?refType=PP&profileId=PP1000000962&deviceType=web&_=1481553927437
  //Top Charts 排行榜
  //https://mtg.now.com/moov/api/profile/getProfileList?refType=PCC&categoryId=PCC1000000002&deviceType=web&page=1&_=1481553927460
  //Daily Chart 日日榜
  //https://mtg.now.com/moov/api/profile/getProfile?refType=PC&profileId=PC1000000002&deviceType=web&_=1481553927461
  $(document).keypress(function(e) {
    if(e.which == 13) {
      $("#function").hide();
      $("#load_more").hide();
      $("#total_result").empty();
      $("#result").empty();
      $search();
    }
  });
  $("#_search").click(function() {
    $("#function").hide();
    $("#load_more").hide();
    $("#total_result").empty();
    $("#result").empty();
    $search();
  });
  $("#_more").click(function() {
    $search(result_from);
  });
  $("#_playlist").click(function() {
    $.post("download.php", {"data": playlist}, function(result) {
      $('<a href="data:application/octet-stream;base64,' + result + '" download="playlist.m3u">')[0].click();
    });
  });
  $("#_downloadlist").click(function() {
    $.post("download.php", {"data": downloadlist}, function(result) {
      $('<a href="data:application/octet-stream;base64,' + result + '" download="download.bat">')[0].click();
    });
  });
</script>
</body>
</html>
