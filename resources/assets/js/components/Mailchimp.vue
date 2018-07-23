<template>
    <el-card class="box-card">
        <!-- Begin MailChimp Signup Form -->
        <div id="mc_embed_signup">
            <h4>Subscribe to the English mailing list to occasionally get notified about updates about the website</h4>
            <form
                action="https://szeremi.us18.list-manage.com/subscribe/post?u=3e48b346f6a62c922a8bcdfdc&amp;id=38cda5401b"
                method="post"
                id="mc-embedded-subscribe-form"
                name="mc-embedded-subscribe-form"
                class="validate"
                target="_blank"
                novalidate
            >
                <div id="mc_embed_signup_scroll">
                    <div class="mc-field-group">
                        <el-input
                            type="email"
                            placeholder="Email address"
                            value=""
                            name="EMAIL"
                            class="required email"
                            id="mce-EMAIL"
                        />
                        <el-button
                            native-type="submit"
                            value="Subscribe"
                            name="subscribe"
                            id="mc-embedded-subscribe"
                            class="button"
                        >Subscribe
                        </el-button>
                    </div>
                    <div id="mce-responses" class="clear">
                        <div class="response" id="mce-error-response" style="display:none"></div>
                        <div class="response" id="mce-success-response" style="display:none"></div>
                    </div>
                    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                    <div style="position: absolute; left: -5000px;" aria-hidden="true"><input
                        type="text"
                        name="b_3e48b346f6a62c922a8bcdfdc_38cda5401b"
                        tabindex="-1"
                        value=""
                    ></div>
                </div>
            </form>
        </div>

        <!--End mc_embed_signup-->
    </el-card>
</template>

<script>
  /** @class Mailchimp */
  export default {
    components: {},
  };
</script>

<style scoped>
    .email {
        width: 180px;
    }

    h4 {
        margin-top: 0;
    }
</style>
