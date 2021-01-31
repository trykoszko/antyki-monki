<?php

namespace Antyki\Olx;

/**
 * OLX cache class
 */

use \Exception as Exception;

/**
 * Class with Ajax methods
 *
 * @since 1.0.0
 */
class Ajax
{
    protected $olxClient;

    public function __construct(\Antyki\Olx\Main $olxClient)
    {
        $this->olxClient = $olxClient;

        $this->loadAjaxHooks();
    }

    public function loadAjaxHooks()
    {
        $hooks = [
            'checkStatus',
            'addAdvert',
            'updateAdvert'
        ];

        foreach ($hooks as $hook) {
            add_action('wp_ajax_' . $hook, [$this, $hook]);
            add_action('wp_ajax_nopriv_' . $hook, [$this, $hook]);
        }
    }

    public function checkStatus()
    {
        try {
            $args = $_REQUEST;
            $nonce = $args['nonce'];
            if (!isset($nonce) || !$nonce) {
                throw new Exception('AJAX: Nonce validation error');
            }

            $authTest = $this->olxClient->authTest();
            $isAuth = $this->olxClient->auth->isAuthenticated;

            wp_send_json_success($authTest || $isAuth);
            wp_die();
        } catch (Exception $e) {
            wp_send_json_error([
                'response' => $e->getMessage(),
            ]);
            wp_die();
        }
    }

    /**
     * Register AJAX action for adding OLX advert
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function addAdvert()
    {
        // arguments from AJAX request
        $args = $_REQUEST;
        $productId = $args['productId'];
        $advert = $this->olxClient->requests->addAdvert($productId);

        // return json response
        echo \json_encode($advert);
        \wp_die();
    }

    public function updateAdvert()
    {
        // arguments from AJAX request
        $args = $_REQUEST;
        $productId = $args['productId'];
        $advert = $this->olxClient->requests->updateAdvert($productId);

        // return json response
        echo \json_encode($advert);
        \wp_die();
    }

    // public function __construct($duplicator_instance)
    // {
    //     $this->duplicator = $duplicator_instance;
    // }

    // public function ajax_save_shop_to_review()
    // {
    //     try {

    //         $req = $_REQUEST;
    //         if (!$req) {
    //             throw new Exception('Request failed');
    //         }

    //         $nonce = isset($req['security']) ? $req['security'] : null;
    //         if (!$nonce || !wp_verify_nonce($nonce, LOOP_SES_SHOPS_NONCE)) {
    //             throw new Exception('Security verification failed');
    //         };

    //         $user_id = isset($req['userId']) ? $req['userId'] : null;
    //         if (!$user_id || !user_can($user_id, LOOP_SES_SHOPS_OWNER_RIGHTS)) {
    //             throw new Exception('You\'re not allowed to edit this shop');
    //         }

    //         $save_for_review = isset($req['isForReview']) ? $req['isForReview'] : null;
    //         if (!$save_for_review) {
    //             throw new Exception('Your permissions don\'t allow you to edit this shop');
    //         }

    //         $post_id = isset($req['postId']) ? $req['postId'] : null;
    //         if (!$post_id) {
    //             throw new Exception('Post ID not provided');
    //         }

    //         $post = get_post($post_id);
    //         if ($post->post_author != $user_id) {
    //             throw new Exception('You\'re not the owner of this shop');
    //         }

    //         // duplicate a post
    //         $duplicated_post_id = $this->duplicator->duplicate($post_id, $req);
    //         if (!$duplicated_post_id) {
    //             throw new Exception('The shop couldn\'t be duplicated');
    //         }

    //         wp_send_json_success([
    //             'errors' => null,
    //             'response' => 'Your edits have been saved and sent to review',
    //             'post_id' => $duplicated_post_id
    //         ]);
    //         wp_die();
    //     } catch (Exception $e) {

    //         wp_send_json_error([
    //             'response' => '<strong>An error occured when submitting review:</strong> <br /> - ' . $e->getMessage(),
    //             'error' => $e->getMessage(),
    //             'post_id' => null
    //         ]);
    //         wp_die();
    //     }
    // }

    // public function ajax_reject_or_accept_review()
    // {
    //     try {

    //         $req = $_REQUEST;
    //         if (!$req) {
    //             throw new Exception('Request failed');
    //         }

    //         $nonce = isset($req['security']) ? $req['security'] : null;
    //         if (!$nonce || !wp_verify_nonce($nonce, LOOP_SES_SHOPS_NONCE)) {
    //             throw new Exception('Security verification failed');
    //         };

    //         $user_id = isset($req['userId']) ? $req['userId'] : null;
    //         if (!$user_id || !user_can($user_id, LOOP_SES_SHOPS_ADMIN_RIGHTS)) {
    //             throw new Exception('You\'re not allowed to accept or reject shops');
    //         }

    //         $post_id = isset($req['postId']) ? $req['postId'] : null;
    //         if (!$post_id) {
    //             throw new Exception('Post ID not provided');
    //         }

    //         $reject_or_accept = isset($req['rejectOrAccept']) ? $req['rejectOrAccept'] : null;
    //         if (!$reject_or_accept) {
    //             throw new Exception('You didn\'t specify the action');
    //         }

    //         $original_id = intval(get_post_meta($post_id, LOOP_SES_SHOPS_ORIGINAL_META_KEY, true));
    //         if (!$original_id) {
    //             throw new Exception('No original post found for this duplicate');
    //         }

    //         $will_reject = false;
    //         $will_update = false;

    //         $reject_or_accept_label = null;

    //         if ($reject_or_accept === 'reject_review') {
    //             $will_reject = $this->duplicator->discard_duplicate($original_id);
    //             if (!$will_reject || !isset($will_reject['result']) || $will_reject['result'] != 1) {
    //                 throw new Exception(
    //                     isset($will_reject['explanation']) ? $will_reject['explanation'] : '[Revision Rejection] Something went wrong. Please contact administrator'
    //                 );
    //             }
    //             $reject_or_accept_label = __t('Edits have been rejected.');
    //         } else if ($reject_or_accept === 'accept_review') {
    //             $will_update = $this->duplicator->accept_duplicate($original_id);
    //             if (!$will_update || !isset($will_update['result'])) {
    //                 throw new Exception(
    //                     isset($will_update['explanation']) ? $will_update['explanation'] : '[Revision Acceptation] Something went wrong. Please contact administrator'
    //                 );
    //             }
    //             $reject_or_accept_label = __t('Edits have been accepted');
    //         }

    //         wp_send_json_success([
    //             'response' => $reject_or_accept_label,
    //             'error' => null,
    //             'post_updated' => $will_update,
    //             'post_rejected' => $will_reject,
    //             'redirect_url' => $reject_or_accept ? admin_url('edit.php?post_type=shop') : null
    //         ]);
    //         wp_die();
    //     } catch (Exception $e) {
    //         wp_send_json_error([
    //             'response' => '<strong>An error occured when accepting/rejecting review:</strong> <br /> - ' . $e->getMessage(),
    //             'error' => $e->getMessage(),
    //             'post_updated' => $will_update,
    //             'post_rejected' => $will_reject,
    //             'redirect_url' => null
    //         ]);
    //         wp_die();
    //     }
    // }
}
