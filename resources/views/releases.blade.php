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
                            <li class="releases"><a class="active" href="/releases"><i
                                        class="fa fa-music"></i><span>Releases</span></a></li>
                            <li class="payout"><a href="/payout"><i
                                        class="fa fa-money-check"></i><span>Payout</span></a></li>
                            <li class="profile"><a href="/profile"><i class="fa fa-user"></i><span>Profile</span></a>
                            </li>
                            <li><a class="logout" href="/logout"><i
                                        class="fa fa-right-from-bracket"></i><span>Logout</span></a></li>
                        </ul>
                    </nav>
                </div>
            </aside>
            <main>
                <div class="padding">
                    <div class="content">
                        <h1>Releases</h1>
                        <div class="row">
                            <div class="col-12">
                                <table border="0" cellspacing="0" cellpadding="0">
                                    <tbody>
                                        @foreach ($releases as $release)
                                            <tr class="track" data-aos="fade-up">
                                                <td class="thumbnail">
                                                    <div class="flex">
                                                        <img width="50" height="50" src="{!! $release['image'] !!}"
                                                            alt="Artwork">
                                                        <div class="by">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="date">
                                                    {!! $release['date'] !!}
                                                </td>
                                                <td class="title">
                                                    {!! $release['title'] !!}
                                                </td>
                                                <td class="streams">
                                                    {{ $release['streams'] }} <small>Streams</small>
                                                </td>
                                                <td class="downloads">
                                                    {{ $release['downloads'] }} <small>Downloads</small>
                                                </td>
                                                <td class="earnings">
                                                    {{ $release['income'] }} <small>€</small>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>

<script src="/js/apexcharts.js"></script>
<script src="/js/apexcharts/earnings.js"></script>
<script src="/js/apexcharts/lastReaches.js"></script>
<script src="/js/apexcharts/efficiency.js"></script>
{{-- <script src="/js/apexcharts/total.js"></script> --}}
<script src="/js/analytics.js"></script>
<script async src="https://www.googletagmanager.com/gtag/js?id=G-9P4DS23DR6"></script>
