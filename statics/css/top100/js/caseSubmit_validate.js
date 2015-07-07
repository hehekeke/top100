/**
 * Created by Administrator on 15-7-7.
 */
$(function(){
$("#subtn").click(function(){
    if($("#case_name").val()=="" || $("#case_name").val()==null){
        alert("案例提案名称不能为空");
        return false;
    }
    if($("#share_name").val()=="" || $("#share_name").val()==null){
        alert("分享者姓名不能为空");
        return false;
    }
    if($("#share_intruduction").val()=="" || $("#share_intruduction").val()==null){
        alert("分享者介绍不能为空");
        return false;
    }
    if($("#share_phone").val()=="" || $("#share_phone").val()==null){
        alert("手机号码不能为空");
        return false;
    }else{
        var reg = "/^(?:13d|15[0|1|2|3|5|6|7|8|9]|18[0|1|2|3|4|5|6|7|8|9])-?d{5}(d{3}|*{3})$/";
        var phone=$("#share_phone").val();

        if(!(phone && /^1[3|4|5|8]\d{9}$/.test(phone))){
            alert("请输入正确的手机号码");
            return false;
        }
    }

    if($("#share_email").val()=="" || $("#share_email").val()==null){
        alert("Email不能为空");
        return false;
    }else{
        var name = $('#share_email').val();
        if(!(name.match(/^[a-z0-9]+([._]*[a-z0-9]+)*@[a-z0-9]+([_.][a-z0-9]+)+$/gi))){
            alert("请输入一个有效的邮件地址");
            return false;
        }
    }

    if($("#share_img").val()=="" || $("#share_img").val()==null){
        alert("分享者照片不能为空");
        return false;
    }
    if($("#team_name").val()=="" || $("#team_name").val()==null){
        alert("所在软件研发中心名称不能为空");
        return false;
    }
    if($("#team_logo").val()=="" || $("#team_logo").val()==null){
        alert("软件研发中心logo不能为空");
        return false;
    }
    if($("#team_intruduction").val()=="" || $("#team_intruduction").val()==null){
        alert("所在软件研发中心介绍不能为空");
        return false;
    }
    if($("#team_intruduction").val()=="" || $("#team_intruduction").val()==null){
        alert("分享者在所属团队的职位不能为空");
        return false;
    }

    if($("#team_nums").val()=="" || $("#team_nums").val()==null){
        alert("所在研发团队规模不能为空");
        return false;
    }

    if($("#team_dingwei").val()=="" || $("#team_dingwei").val()==null){
        alert("该研发团队职能定位不能为空");
        return false;
    }

    if($("#case_desc").val()=="" || $("#case_desc").val()==null){
        alert("案例简述不能为空");
        return false;
    }
    if($("#case_50").val()=="" || $("#case_50").val()==null){
        alert("案例解读大纲不能为空");
        return false;
    }


    return false;
});
});
