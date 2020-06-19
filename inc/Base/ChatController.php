<?php

/**
 * @package  eMentoringPlugin
 */

namespace Inc\Base;

use Inc\Base\BaseController;

/**
 * 
 */
class ChatController extends BaseController
{
    public function register()
    {
        add_shortcode('chat', array($this, 'ementoring_shortcode'));
    }

    public function ementoring_shortcode()
    {
        $output = <<<CODE
<div class="container">
        <div class="emp-nav">
            <div class="nav-container">
                <h4 id="nav-header-1">Settings</h4>
            </div>

            <div class="nav-profile-avatar">
                <img src="https://cdn3.vectorstock.com/i/1000x1000/30/97/flat-business-man-user-profile-avatar-icon-vector-4333097.jpg" alt="profile avatar" />
            </div>
        </div>

        <div id="settings-container">
            <div id="rooms-column">
                <a href="#" style="text-decoration: none;" class="rooms">
                    <img src="https://www.shareicon.net/data/512x512/2016/05/24/770137_man_512x512.png" alt="Profile Avatar" />
                    <p class="emp-ptext">Micheal Jackson</p>
                </a>

                <a href="#" style="text-decoration: none;" class="rooms">
                    <img src="https://w7.pngwing.com/pngs/728/963/png-transparent-nauticoncept-custom-arts-store-mobile-phones-user-avatar-miscellaneous-english-heroes.png" alt="Profile Avatar" />
                    <p class="emp-ptext">John Doe</p>
                </a>

                <a href="#" style="text-decoration: none;" class="rooms">
                    <img src="https://www.pngarts.com/files/6/User-Avatar-in-Suit-PNG.png" alt="Profile Avatar" />
                    <p class="emp-ptext">Johnny Wang</p>
                </a>
            </div>
            <div id="settings-column">
                <div class="setting-options">
                    <div class="left-container">
                        <p class="options-title">Name</p>
                        <p>Jeff Trinh</p>
                    </div>

                    <div class="right-container">
                        <button class="edit-button">Edit</button>
                    </div>
                </div>

                <div class="setting-options">
                    <div class="left-container">
                        <p class="options-title">E-mail</p>
                        <p>example@abc.xyz</p>
                    </div>

                    <div class="right-container">
                        <button class="edit-button">Edit</button>
                    </div>
                </div>

                <div class="setting-options">
                    <div class="left-container">
                        <p class="options-title">Password</p>
                        <p>***********</p>
                    </div>

                    <div class="right-container">
                        <button class="edit-button">Edit</button>
                    </div>
                </div>
            </div>
        </div>
</div>

CODE;
        return $output;
    }
}
