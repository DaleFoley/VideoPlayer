<?php

function startsWith($haystack, $needle)
{
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
}

function outputLoggedOutNavBar()
{
    echo '<nav class="navbar navbar-expand-md navbar-dark bg-dark">
            <a class="navbar-brand fas fa-video" href="/">
                Video Player
            </a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/login">Login</a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="/signup">Register</a>
                    </li>
                </ul>
            </div>
        </nav>';
}

function outputLoggedInNavBar($container)
{
    $username = "";
    $user = $container->session->get(USER);

    if($user !== null)
    {
        $username = "Logged In User: " . $user->name;
    }

    echo '<nav class="navbar navbar-expand-md navbar-dark bg-dark">
            <a class="navbar-brand fas fa-video" href="/">
                Video Player ' . $username . '
            </a>
            
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/logout">Logout</a>
                    </li>
                </ul>
            </div>
        </nav>';
}

function outputNavBar($container)
{
    $isLoggedIn = $container->session->get(IS_LOGGED_IN);
    if($isLoggedIn)
    {
        outputLoggedInNavBar($container);
    }
    else
    {
        outputLoggedOutNavBar();
    }
}