<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Document</title>
    <link href=’https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/ui-lightness/jquery-ui.css’
     rel=’stylesheet’>
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" 
    integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
     <link href=
'https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/ui-lightness/jquery-ui.css'
          rel='stylesheet'>
      
    <script src=
"https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js" >
    </script>
      
    <script src=
"https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" >
    </script>
</head>
<body>
    
@csrf
Start-date <input type="text" name="sdate" id="start-date" value="" autocomplete="off">
   End-date <input type="text" name="edate" id="end-date" value="" autocomplete="off">
   <select name="" id="bwdate">
    <option value="no">select-date</option>
    <option value="1">Today</option>
    <option value="7">Last-7days</option>
    <option value="30">Last-30days</option>
    <option value="0">Custom</option>
   </select>
<select name="" id="cmpid">
        <option value="">select company-id</option>
        @foreach($companyid as $companyids)
        <option value="{{$companyids->company_id}}">{{$companyids->company_id}}</option>
        @endforeach
    </select>
    <select name="" id="country">
    <option value="">select-country</option>
    
    </select>
   
    <select name="" id="department">
    <option value="">selecet-department</option>
   
    </select>
   
    <select name="" id="employee">
    <option value="">selecet-employee</option>
   
    </select>
    
   <button class="btn btn-success" id="sub">submit</button>
   <div class="container">
   <table class="table">
    <thead>
        <tr>
            <th>id</th>
            <th>fname</th>
            <th>lname</th>
            <th>company_id</th>
            <th>date-of-birth</th>
            <th>department-value</th>
            <th>employee-value</th>
            <th>start-date</th>
            <th>country</th>
        </tr>
    </thead>
    <tbody id="tb">
        
    </tbody>
   </table>
   </div>
   <style>
    body{
        font-size:15px;
        margin-left:5%;
    }
    #start-date,#end-date{
        width:120px;
    }
   </style>
  
</body>
<script>
    $("#start-date").datepicker({dateFormat:"dd-mm-yy",changeMonth: true,changeYear: true});
    $("#end-date").datepicker({dateFormat:'dd-mm-yy',changeMonth: true,changeYear: true});
    
    //date submit
    $("#cmpid").on('change', function(){
        $('.opt').remove();
        
    var country=$("#country").val();
    var department=$("#department").val();
    var employee=$("#employee").val()
    var cmpid=$("#cmpid").val();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

        $.ajax({
            url:"{{ url('company') }}",
            type:"POST",
            data:{cmpid:cmpid},
            success: function(data){
                for(let country of data.countryval){
                    var cry=`<option value="${country.id}" class='opt'>${country.country}</option>`;
                    $('#country').append(cry);
                }
                for(let Dvalue of data.dvalue){
                    var dval=`<option value="${Dvalue.id}"class='opt'>${Dvalue.value}</option>`;
                    $('#department').append(dval);
                }
                for(let Evalue of data.evalue){
                    var eval=`<option value="${Evalue.id}" class='opt'>${Evalue.value}</option>`;
                    $('#employee').append(eval);
                }
                // console.log(data.countryval);
                // console.log(cry);
            },
            error: function(data){
                // console.log(data);
            }
        });

    });
    
var tr=null;
    $("#sub").click(function(){
        if(tr!=null){
        $(".tr").remove();
        }
    //  document.location.reload(true);
// window.location.reload();

        
    var startdate=$("#start-date").val();
    var sdate=new Date(startdate);
    var enddate=$("#end-date").val();
    var edate=new Date(enddate);

    var country=$("#country").val();
    var department=$("#department").val();
    var employee=$("#employee").val();
    var cmpid=$("#cmpid").val();
    // strdate=sdate.getFullYear()+'-'+sdate.getDate()+'-'+(sdate.getMonth()+1);
    // edrdate=edate.getFullYear()+'-'+edate.getDate()+'-'+(edate.getMonth()+1);
    // console.log(enddate);
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
   
    $.ajax({
        url:"{{ url('index') }}",
        type:"POST",
        data:{startdate:startdate,enddate:enddate,country:country,department:department,employee:employee,cmpid:cmpid},
        dataType: 'JSON',
        success:function(datas){
           
            console.log(datas);
            for(let data of datas){
                var sdate=new Date(data.start_date).toDateString();
                var bdate=new Date(data.date_of_birth).toDateString();
             tr=`<tr class='tr'>
                    <td>${data.id}</td>
                    <td>${data.fname}</td>
                    <td>${data.lname}</td>
                    <td>${data.company_id}</td>
                    <td>${bdate}</td>
                    <td>${data.value}</td>
                    <td>${data.evalue}</td>
                    <td>${sdate}</td>
                    <td>${data.country}</td>
                    </tr>
                    `;
                $("#tb").append(tr);
            }
        },
        error: function(data){
                // console.log(data);
    }
});

});


$('#bwdate').on('change', function(){
    var bwdate=$('#bwdate').val();
    if(bwdate==1){
        $('#start-date').prop("disabled", true);
        $('#end-date').prop("disabled", true);
        let tday=new Date();
        $('#start-date').val(tday.getDate()+'-'+(tday.getMonth()+1)+'-'+tday.getFullYear());
        $('#end-date').val(tday.getDate()+'-'+(tday.getMonth()+1)+'-'+tday.getFullYear());
    }
    if(bwdate=='no'){
        $('#start-date').prop("disabled", false);
        $('#end-date').prop("disabled", false);
        $('#start-date').val('');
        $('#end-date').val('');
    }
    if(bwdate==7){
        $('#start-date').prop("disabled", true);
        $('#end-date').prop("disabled", true);
        let eday=new Date();
        let tday=new Date();
        eday.setDate(eday.getDate()-7);

        $('#start-date').val(eday.getDate()+'-'+(eday.getMonth()+1)+'-'+eday.getFullYear());
        $('#end-date').val(+tday.getDate()+'-'+(tday.getMonth()+1)+'-'+tday.getFullYear());
    }
    if(bwdate==30){
        $('#start-date').prop("disabled", true);
        $('#end-date').prop("disabled", true);
        let eday=new Date();
        let tday=new Date();

        eday.setDate(eday.getDate()-30);
        $('#start-date').val(eday.getDate()+'-'+(eday.getMonth()+1)+'-'+eday.getFullYear());
        $('#end-date').val(tday.getDate()+'-'+(tday.getMonth()+1)+'-'+tday.getFullYear());
        
    }
    if(bwdate==0){
        $('#start-date').prop("disabled", false);
        $('#end-date').prop("disabled", false);
        let eday=new Date();
        let tday=new Date();
        

        eday.setDate(eday.getDate()-365);
        $('#start-date').val(eday.getDate()+'-'+(eday.getMonth()+1)+'-'+eday.getFullYear());
        $('#end-date').val(tday.getDate()+'-'+(tday.getMonth()+1)+'-'+tday.getFullYear()); 
        $('#start-date').on('change',function(){
            let sdate=$('#start-date').val();
        let e=new Date(sdate).getTime();
        let s=new Date(eday).getTime();
        let st=new Date(sdate)

            if(s>e){
                // console.log(s);
                // console.log(e);
                // alert('select only last onyear date');
        st.setDate(st.getDate()+365)
        $('#end-date').val(st.getDate()+'-'+(st.getMonth()+1)+'-'+st.getFullYear()); 


            }
        })
    }
})



//change country
$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
// $("#country").click(function(){
//     var country=$("#country").val();
    
//     $.ajax({
//         url:"{{url('country')}}",
//         type:"POST",
//         data:{country:country},
//         dataType:'JSON',
//         success: function(datas){
//             console.log(datas);
//             for(let data of datas){
//             var tr=`<tr>
//                     <td>${data.id}</td>
//                     <td>${data.state}</td>
//                     <td>${data.state_code}</td>
//                     <td>${data.company_id}</td>
//                     </tr>
//                     `;
//                 $("#tb").append(tr);
//             }
//         },
//         error: function(data){
//             console.log(data);
//         }
//     });
// });
</script>
</html>