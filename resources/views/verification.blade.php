@extends('layouts.app')

@section('content')

<style>
.verification-page{
    display:flex;
    flex-direction:column;
    gap:18px;
}

.verify-hero{
    position:relative;
    overflow:hidden;
    border-radius:30px;
    padding:22px;
    background:
        linear-gradient(135deg, rgba(5,10,7,0.58) 0%, rgba(5,10,7,0.80) 100%),
        radial-gradient(circle at 78% 24%, rgba(184,255,59,0.20) 0%, rgba(184,255,59,0) 30%),
        radial-gradient(circle at 20% 80%, rgba(98,255,154,0.08) 0%, rgba(98,255,154,0) 28%),
        linear-gradient(135deg,#0d1711 0%, #13211a 55%, #0a120d 100%);
    border:1px solid rgba(184,255,59,0.12);
    box-shadow:0 18px 40px rgba(0,0,0,0.28);
}

.verify-title{
    font-size:28px;
    font-weight:900;
    color:#f4fff8;
    margin-bottom:8px;
}

.verify-sub{
    color:#b9c8bf;
    font-size:14px;
    line-height:1.8;
}

.verify-card{
    border-radius:28px;
    padding:20px;
    background:linear-gradient(180deg,#101b15 0%, #0c1410 100%);
    border:1px solid rgba(184,255,59,0.10);
    box-shadow:0 14px 30px rgba(0,0,0,0.22);
}

.verify-grid{
    display:grid;
    grid-template-columns:1fr;
    gap:16px;
}

.verify-upload{
    border-radius:22px;
    padding:18px;
    background:#121c16;
    border:1px dashed rgba(184,255,59,0.18);
}

.verify-label{
    display:block;
    font-size:15px;
    font-weight:800;
    color:#f4fff8;
    margin-bottom:8px;
}

.verify-help{
    font-size:13px;
    color:#8da095;
    line-height:1.7;
    margin-bottom:12px;
}

.verify-input{
    width:100%;
    border:none;
    outline:none;
    border-radius:16px;
    padding:14px;
    background:#18251b;
    color:#f4fff8;
    border:1px solid rgba(184,255,59,0.08);
}

.verify-preview{
    margin-top:14px;
    display:none;
    width:100%;
    max-height:240px;
    object-fit:cover;
    border-radius:18px;
    border:1px solid rgba(184,255,59,0.10);
}

.verify-btn{
    border:none;
    border-radius:20px;
    padding:16px;
    font-size:16px;
    font-weight:900;
    background:radial-gradient(circle,#c7ff61 0%,#8dff1c 100%);
    color:#07120b;
    box-shadow:0 10px 24px rgba(184,255,59,0.18);
    cursor:pointer;
    width:100%;
}

.verify-alert-error{
    padding:14px 16px;
    border-radius:18px;
    font-size:14px;
    font-weight:700;
    background:rgba(255,95,116,0.10);
    border:1px solid rgba(255,95,116,0.18);
    color:#ffd3da;
}

.verify-alert-success{
    padding:14px 16px;
    border-radius:18px;
    font-size:14px;
    font-weight:700;
    background:rgba(61,213,152,0.10);
    border:1px solid rgba(61,213,152,0.18);
    color:#a7f3d0;
}

@media (max-width:700px){
    .verify-title{
        font-size:24px;
    }
}
</style>

<div class="verification-page">

    @if(session('success'))
        <div class="verify-alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="verify-alert-error">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="verify-hero">
        <div class="verify-title">Identity Verification</div>
        <div class="verify-sub">
            ارفع صورة البطاقة من الأمام، وصورة البطاقة من الخلف، وصورة سيلفي واضحة وأنت تمسك البطاقة بجانب وجهك. الإدارة ستراجع الطلب يدويًا.
        </div>
    </div>

    <div class="verify-card">
        <form method="POST" action="/verification" enctype="multipart/form-data">
            @csrf

            <div class="verify-grid">

                <div class="verify-upload">
                    <label class="verify-label">ID Card Front</label>
                    <div class="verify-help">ارفع صورة واضحة للجهة الأمامية من البطاقة الشخصية.</div>
                    <input type="file" name="front_image" class="verify-input" accept="image/*" onchange="previewImage(this, 'frontPreview')" required>
                    <img id="frontPreview" class="verify-preview" alt="Front Preview">
                </div>

                <div class="verify-upload">
                    <label class="verify-label">ID Card Back</label>
                    <div class="verify-help">ارفع صورة واضحة للجهة الخلفية من البطاقة الشخصية.</div>
                    <input type="file" name="back_image" class="verify-input" accept="image/*" onchange="previewImage(this, 'backPreview')" required>
                    <img id="backPreview" class="verify-preview" alt="Back Preview">
                </div>

                <div class="verify-upload">
                    <label class="verify-label">Selfie With ID</label>
                    <div class="verify-help">التقط صورة واضحة لوجهك وأنت تمسك البطاقة بجانبك بشكل ظاهر ومقروء.</div>
                    <input type="file" name="selfie_image" class="verify-input" accept="image/*" onchange="previewImage(this, 'selfiePreview')" required>
                    <img id="selfiePreview" class="verify-preview" alt="Selfie Preview">
                </div>

                <button type="submit" class="verify-btn">Submit Verification</button>

            </div>
        </form>
    </div>

</div>

<script>
function previewImage(input, previewId){
    const file = input.files[0];
    const preview = document.getElementById(previewId);

    if(!file || !preview) return;

    const reader = new FileReader();
    reader.onload = function(e){
        preview.src = e.target.result;
        preview.style.display = 'block';
    };
    reader.readAsDataURL(file);
}
</script>

@endsection