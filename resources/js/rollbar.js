import { init } from "rollbar";

//export const rollbar = init({
//  accessToken: window.vars.rollbarToken,
//  captureUncaught: true,
//  captureUnhandledRejections: true,
//  enabled: true,
//  source_map_enabled: true,
//  environment: window.vars.environment,
//  payload: {
//    client: {
//      javascript: {
//        code_version: window.vars.gitSha1,
//      },
//    },
//  },
//  checkIgnore(isUncaught, args, payload) {
//    if (window.navigator.userAgent && window.navigator.userAgent.indexOf('PetalBot') !== -1) {
//      // ignore PetalBot
//      return true;
//    }
//    // no other ignores
//    return false;
//  },
//});
