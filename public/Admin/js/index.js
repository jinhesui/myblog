//JS简单问候语函数
function showTime(){
    //定义问候语变量
    var str = "";
    //获取id=showTime的元素
    var liObj = document.getElementById("showTime");
    //创建日期对象实例
    var today = new Date();
    //取出小时数
    var hours = today.getHours();
    //根据小时数，输出不同的问候语
    if (parseInt(hours)>=0 && parseInt(hours)<=11) {
        str = "早上好";
    } else if (parseInt(hours)>11 && parseInt(hours)<=13) {
        str = "中午好";
    } else if (parseInt(hours)>13 && parseInt(hours)<=17) {
        str = "下午好";
    } else {
        str = "晚上好";
    }
    //向id=showTime元素写入内容
    liObj.innerHTML = str;
}
window.onload = showTime;