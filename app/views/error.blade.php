
@include('layouts._header')
<style>
    main {
        padding-top: 150px;
    }
    .errorCtn{
        padding: 5px;
    }
</style>
<section class="wrapperBG">
    <div class="container">
        <div class="row">
            <main>
            <section class="errorCtn text-info">
                <h4><?php echo $ex?></h4>
                <a class="btn btn-default" href="/">Về trang chủ</a>
            </section>
            </main>
            @include('layouts._footer')