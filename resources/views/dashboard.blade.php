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
                            <li class="overview"><a class="active" href="/dashboard"><i
                                        class="fa fa-house"></i><span>Overview</span></a></li>
                            <li class="releases"><a href="/releases"><i
                                        class="fa fa-music"></i><span>Releases</span></a></li>
                            <li class="payout"><a href="/payout"><i
                                        class="fa fa-money-check"></i><span>Payout</span></a></li>
                            <li class="profile"><a href="/profile"><i class="fa fa-user"></i><span>Profile</span></a>
                            </li>
                            <li><a class="logout" href="/logout"><i
                                        class="fa fa-right-from-bracket"></i><span>Logout</span></a></li>
                            {{-- <li class="documents"><a href="/documents"><i class="fa fa-file-invoice"></i><span>Documents</span></a></li> --}}
                        </ul>
                    </nav>
                </div>
                {{-- <div class="logo"><a href="/"><img src="/images/branding.svg"></a></div> --}}
                {{-- <div class="report">
            <img src="/images/report.png">
            <p class="h4 headline">Payout<span class="small">Claim your well-deserved earnings right away</span></p><a class="btn" href="/payout">Request now</a>
            {{-- <p class="h4 headline">PDF Report<span class="small">Detailed monthly report</span></p><a class="btn" href="/documents#newest">Download</a>
          </div> --}}
            </aside>
            <main>
                <div class="padding">
                    {{-- <div class="iconBar">
              {{-- <ul>
                <li>
                  <button class="wallet"><i class="fa fa-wallet"></i></button>
                </li>
              </ul>
              <ul></ul>
              <ul>
                {{-- <li>
                  <button class="currency"><i class="fa fa-euro-sign"></i></button>
                </li>
                <li>
                  <button class="notifications"><i class="fa fa-bell"></i></button>
                </li>
                <li><a class="logout" href="/logout"><i class="fa fa-right-from-bracket"></i></a></li>
              </ul>
            </div> --}}
                    <div class="overview">
                        {{-- <h1>Overview</h1> --}}
                        <div class="grid">
                            <div class="card">
                                <button class="wallet">
                                    <div class="tilt"><img src="{{ mix('/resources/images/paypal.svg') }}">
                                        <div class="flex">
                                            <div class="left">
                                                <p class="small uppercase">Balance</p>
                                                <span>€</span><span>{!! $balance !!}</span>
                                            </div>
                                            <div class="right">
                                                <p class="small uppercase">Holder</p>
                                                <span>{!! $user['first_name'] ?? $user['name'] !!}</span>
                                            </div>
                                        </div>
                                    </div>
                                </button>
                            </div>
                            <div id="earnings">
                                <div class="flex"><span class="headline">Earnings</span>
                                    <div class="utilities">
                                        <ul>
                                            <li class="eur">
                                                <span>EUR</span>
                                            </li>
                                            {{-- <li class="usd">
                          <button>USD</button>
                        </li>
                        <li class="cny">fd vc
                          <button>CNY</button>
                        </li> --}}
                                        </ul>
                                    </div>
                                </div>
                                <div class="apexcharts" data-js="{!! $earnings !!}"></div>
                            </div>
                            <div id="lastReaches">
                                <div class="flex"><span class="headline">Last reaches</span></div>
                                <div class="apexcharts" data-js-streams="{!! $lastReachesStreams !!}"
                                    data-js-downloads="{!! $lastReachesDownloads !!}"></div>
                            </div>
                            <div id="efficiency">
                                <div class="flex"><span class="headline">Efficiency</span></div>
                                <div class="apexcharts" data-js="{!! $efficiency !!}"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            {{-- <aside class="right">
          <div class="iconBar">
            <ul>
              <li>
                <button class="close"><i class="fa fa-xmark"></i></button>
              </li>
            </ul>
          </div>
          <div class="user"><a href="/profile"><img src="{!! $user['avatar'] !!}"></a>
            <p class="h3 headline"><span>{!! $user['firstName'] !!}</span><span>&nbsp;</span><span>{!! $user['lastName'] !!}</span><span class="small">Artist</span></p>
          </div>
          <div id="total">
            <p class="h2 headline"><span class="small top">Balance</span><span>€</span><span>{!! $balance['allMonths'] !!}</span>
            </p>
            <div class="apexcharts"></div>
          </div>
        </aside> --}}
        </div>
    </div>
</div>

<script src="{{ mix('/resources/js/apexcharts.js') }}"></script>
<script src="{{ mix('/resources/js/apexcharts/earnings.js') }}"></script>
<script src="{{ mix('/resources/js/apexcharts/lastReaches.js') }}"></script>
<script src="{{ mix('/resources/js/apexcharts/efficiency.js') }}"></script>
{{-- <script src="/js/apexcharts/total.js"></script> --}}
<script src="{{ mix('/resources/js/analytics.js') }}"></script>
<script async src="https://www.googletagmanager.com/gtag/js?id=G-9P4DS23DR6"></script>
