<div class="row">
    <div class="col-12">
        <div class="page">
            <aside class="left">
                <div class="user"><a href="/profile"><img src="{!! $avatar ?? mix('/resources/images/user.jpg') !!}"></a>
                    <p class="h3 headline">{!! $user['name'] !!} <span class="small">Artist</span></p>
                </div>

                <div class="navigation">
                    <nav>
                        <ul>
                            <li class="overview"><a href="/dashboard"><i
                                        class="fa fa-house"></i><span>Overview</span></a></li>
                            <li class="releases"><a href="/releases"><i
                                        class="fa fa-music"></i><span>Releases</span></a></li>
                            <li class="payout"><a href="/payout"><i
                                        class="fa fa-money-check"></i><span>Payout</span></a></li>
                            <li class="profile"><a class="active" href="/profile"><i
                                        class="fa fa-user"></i><span>Profile</span></a></li>
                            <li><a class="logout" href="/logout"><i
                                        class="fa fa-right-from-bracket"></i><span>Logout</span></a></li>
                        </ul>
                    </nav>
                </div>
            </aside>
            <main>
                <div class="padding">
                    <div class="content">
                        <h1>Profile</h1>
                        <div class="row">
                            <div class="col-12">
                                <form id="avatarForm" method="POST" action="/profile" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="id" value="avatarForm">
                                    <div class="box">
                                        <p class="headline">Change your profile picture</p>
                                        <div class="row">
                                            <div class="col-12 col-lg-6">
                                                <label for="avatar">Picture <small>(only .jpg & .jpeg, max
                                                        500kb)</small></label>
                                                <input id="avatar" type="file" name="avatar" accept=".jpg, .jpeg"
                                                    required>
                                            </div>
                                            <div class="col-12">
                                                <div class="flex">
                                                    <input class="btn" type="submit" value="Save picture">
                                                    <input class="btn secondary" type="reset" value="Cancel">
                                                </div>
                                                <div class="output">
                                                    @if ($errors->has('avatarMessage'))
                                                        <div class="border-error">{{ $errors->first('avatarMessage') }}
                                                        </div>
                                                    @endif
                                                    @if (session()->has('avatarSuccess'))
                                                        <div class="border-success">{{ session('avatarSuccess') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <form id="passForm" method="POST" action="/profile">
                                    @csrf
                                    <input type="hidden" name="id" value="passForm">
                                    <div class="box">
                                        <p class="headline">Change your password</p>
                                        <div class="row">
                                            <div class="col-12 col-lg-6">
                                                <label for="oldPassword">Old password</label>
                                                <input id="oldPassword" type="password" name="oldPassword"
                                                    placeholder="············" required>
                                            </div>
                                            <div class="col-12 col-lg-6">
                                                <label for="newPassword">New password</label>
                                                <input id="newPassword" type="password" name="newPassword"
                                                    placeholder="············" required>
                                            </div>
                                            <div class="col-12 col-lg-6">
                                                <label for="confirmPassword">Confirm new password
                                                    <input id="confirmPassword" type="password" name="confirmPassword"
                                                        placeholder="············" required>
                                                </label>
                                            </div>
                                            <div class="col-12">
                                                <div class="flex">
                                                    <input class="btn" type="submit" value="Save password">
                                                    <input class="btn secondary" type="reset" value="Cancel">
                                                </div>
                                                <div class="output">
                                                    @if ($errors->has('passwordMessage'))
                                                        <div class="border-error">
                                                            {{ $errors->first('passwordMessage') }}</div>
                                                    @endif
                                                    @if (session()->has('passwordSuccess'))
                                                        <div class="border-success">{{ session('passwordSuccess') }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>

<script src="/js/analytics.js"></script>
<script async src="https://www.googletagmanager.com/gtag/js?id=G-9P4DS23DR6"></script>
