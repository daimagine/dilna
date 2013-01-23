@layout('layout.front')

@section('content')
<div>

    <!-- Login wrapper begins -->
    <div class="loginWrapper">

        <div class="loginPic">
            <span>SpringWizard eBengkel Login</span>
            <div class="loginActions">
                <!--div><a href="#" title="Change user" class="logleft flip"></a></div>
            <div><a href="#" title="Forgot password?" class="logright"></a></div-->
            </div>
        </div>

        @include('partial.notification')
        <div class="clear"></div>

        <div>

            <form action="" method="post">
                <input type="text" name="login" placeholder="Confirm your login ID" class="loginEmail" id="loginInput" />
                <input type="password" name="password" placeholder="Password" class="loginPassword" />

                <div class="logControl">
                    <!--            <div class="memory"><input type="checkbox" checked="checked" class="check" id="remember1" /><label for="remember1">Remember me</label></div>-->
                    <input type="submit" name="submit" value="Login" class="buttonM bBlue" />
                    <div class="clear"></div>
                </div>
            </form>
        </div>

    </div>
    <!-- Login wrapper ends -->

</div>
@endsection