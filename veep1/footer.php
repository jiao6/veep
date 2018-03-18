<div class="footer">
    <div class="btm-btm">
        <div class="about">
            <a href="aboutus.php">关于我们</a> |
            <a href="product.php">产品中心</a> |
            <a href="service.php">服务与支持</a> |
            <a href="download.php">下载中心</a>
        </div>
        <div class="info">

            <div>虚拟实验工场 京ICP备16050047号</div>
            <div>地址： 北京海淀区中关村南大街5号  邮编：100081</div>
        </div>
    </div>
</div>

<script type="text/javascript" src="http://223.68.10.47:8088/clickheat/js/clickheat.js"></script>
<script type="text/javascript"><!--
    clickHeatSite = 'veep.chinacloudapp.cn';
    clickHeatGroup = encodeURIComponent(window.location.pathname + window.location.search);
    clickHeatServer = 'http://223.68.10.47:8088/clickheat/click.php';
    initClickHeat(); //-->
</script>

<script type="text/javascript">

    // var height = $('.rt_table').height() +  $('.rt_table').next('div').height();
    // var heightr = $('.rt').height();
    // var contain2height = $('.contain2').height();
    // var containheight = $('.contain').height();
    // if (heightr != null) {
    //     $('.rt').height(heightr + 50);
    //     $('.contain').height(heightr + 120);
    // }
    // if (height != null  ) {
    //     $('.rt').height(height + 150);
    //     $('.contain2').height(height + 220);
    //     $('.contain').height(height + 220);
    // }
    // if(height>heightr){
    //     $('.rt').height(height +150);
    // }

    if (navigator.appName == "Microsoft Internet Explorer" && navigator.appVersion.split(";")[1].replace(/[ ]/g, "") == "MSIE7.0") {
        if ($('.lt').height() > $('.rt').height()){
            $('.footer').css({
                'margin-top':160
            });
        }
    } else {
        if ($('.lt').height() > $('.rt').height()){
            $('.footer').css({
                'margin-top':$('.lt').height()+190
            });
        } else {
            $('.footer').css({
                'margin-top':$('.rt').height()+80
            });
        }
    }



    // if ($('.lt').height() > $('.rt').height()){
    //     $('.footer').css({
    //         'margin-top':$('.lt').height()
    //     });
    // }
    if ($('.left_lt').height() > $('.right_rt').height()){
        $('.footer').css('margin-top',$('.left_lt').height() + 90);
    }
    if ($('.left').height() > $('.right').height()){
        $('.footer').css('margin-top',$('.left').height() + 90);
    }
    
    $('.lt').css({
        'padding-bottom':60
    });
    $('.left').css({
        'padding-bottom':60
    });
    $('.right').css({
        'padding-bottom':60
    });
    $('.rt').css({
        'padding-bottom':180
    });


</script>
</body>
</html>