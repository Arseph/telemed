<style>
    body {
        background: url('{{ asset('public/img/backdrop.png') }}'), -webkit-gradient(radial, center center, 0, center center, 460, from(#ccc), to(#ddd));
    }
    .btnPlan {
        display: none;
        position: fixed;
        bottom: 120px;
        right: 18px;
        z-index: 99;
        font-size: 12px;
        border: none;
        outline: none;
        color: white;
        cursor: pointer;
        padding: 15px;
        border-radius: 50%;
    }
    .btnDiagnosis {
        display: none;
        position: fixed;
        bottom: 180px;
        right: 18px;
        z-index: 99;
        font-size: 12px;
        border: none;
        outline: none;
        color: white;
        cursor: pointer;
        padding: 15px;
        border-radius: 50%;
    }
    .btnCovid {
        display: none;
        position: fixed;
        bottom: 240px;
        right: 18px;
        z-index: 99;
        font-size: 12px;
        border: none;
        outline: none;
        color: white;
        cursor: pointer;
        padding: 15px;
        border-radius: 50%;
    }
    .btnClinical {
        display: none;
        position: fixed;
        bottom: 300px;
        right: 18px;
        z-index: 99;
        font-size: 12px;
        border: none;
        outline: none;
        color: white;
        cursor: pointer;
        padding: 15px;
        border-radius: 50%;
    }
    .btnDemo {
        display: none;
        position: fixed;
        bottom: 360px;
        right: 18px;
        z-index: 99;
        font-size: 12px;
        border: none;
        outline: none;
        color: white;
        cursor: pointer;
        padding: 15px;
        border-radius: 50%;
    }
    #myBtn {
        position: fixed;
        bottom: 54px;
        right: 18px;
        z-index: 99;
        font-size: 18px;
        border: none;
        outline: none;
        background-color: rgba(38, 125, 61, 0.92);
        color: white;
        cursor: pointer;
        padding: 15px;
        border-radius: 10px;
    }
    .btnSaveClinical {
        position: fixed;
        bottom: 120px;
        right: 18px;
        z-index: 99;
        font-size: 18px;
        border: none;
        outline: none;
        background-color: rgba(38, 125, 61, 0.92);
        color: white;
        cursor: pointer;
        padding: 15px;
        border-radius: 50%;
    }
    .btnSaveCovid {
        position: fixed;
        bottom: 120px;
        right: 18px;
        z-index: 99;
        font-size: 18px;
        border: none;
        outline: none;
        background-color: rgba(38, 125, 61, 0.92);
        color: white;
        cursor: pointer;
        padding: 15px;
        border-radius: 50%;
    }
    .btnSaveDemo {
        position: fixed;
        bottom: 120px;
        right: 18px;
        z-index: 99;
        font-size: 18px;
        border: none;
        outline: none;
        background-color: rgba(38, 125, 61, 0.92);
        color: white;
        cursor: pointer;
        padding: 15px;
        border-radius: 50%;
    }
    .btnSaveCovid {
        position: fixed;
        bottom: 120px;
        right: 18px;
        z-index: 99;
        font-size: 18px;
        border: none;
        outline: none;
        background-color: rgba(38, 125, 61, 0.92);
        color: white;
        cursor: pointer;
        padding: 15px;
        border-radius: 50%;
    }
    .btnSaveDiag {
        position: fixed;
        bottom: 120px;
        right: 18px;
        z-index: 99;
        font-size: 18px;
        border: none;
        outline: none;
        background-color: rgba(38, 125, 61, 0.92);
        color: white;
        cursor: pointer;
        padding: 15px;
        border-radius: 50%;
    }
    .btnSavePlan {
        position: fixed;
        bottom: 120px;
        right: 18px;
        z-index: 99;
        font-size: 18px;
        border: none;
        outline: none;
        background-color: rgba(38, 125, 61, 0.92);
        color: white;
        cursor: pointer;
        padding: 15px;
        border-radius: 50%;
    }
    #myBtn:hover {
        background-color: #555;
    }
    .select2 {
        width:100%!important;
    }
    label {
        padding: 0px;
    }
    .form-group {
        margin-bottom: 10px;
    }
    .sidenav {
      height: 100%;
      width: 0;
      position: fixed;
      z-index: 1;
      top: 0;
      left: 0;
      background-color: #fff;
      overflow-x: hidden;
      transition: 0.5s;
      padding-top: 60px;
    }

    .sidenav a {
      padding: 8px 8px 8px 32px;
      text-decoration: none;
      font-size: 25px;
      color: #818181;
      display: block;
      transition: 0.3s;
    }

    .sidenav a:hover {
      color: #f1f1f1;
    }

    .sidenav .closebtn {
      position: absolute;
      top: 0;
      right: 5px;
      font-size: 36px;
      margin-left: 50px;
    }

    @media screen and (max-height: 450px) {
      .sidenav {padding-top: 15px;}
      .sidenav a {font-size: 18px;}
    }
    /* width */
    ::-webkit-scrollbar {
      width: 10px;
    }

    /* Track */
    ::-webkit-scrollbar-track {
      background: #f1f1f1; 
    }
     
    /* Handle */
    ::-webkit-scrollbar-thumb {
      background: #888;
      border-radius: 10px;
    }

    /* Handle on hover */
    ::-webkit-scrollbar-thumb:hover {
      background: #555; 
    }
</style>