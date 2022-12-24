import { captureException } from '@sentry/vue';
import { isProduction } from '../value/env';

const genericErrorMessage = 'An error has occurred.';

export const VALIDATION_FAILURE = 'validation_failure';
export const FILE_TOO_LARGE = 'file_too_large';
export const NOT_FOUND = 'not_found';

/**
 * Normalizes (ajax) errors.
 */
export function normalizeError(error) {
  if (error && error.normalizedError) {
    // Already normalized.
    return error;
  }

  const ret = { message: genericErrorMessage, normalizedError: true };
  let response, status, data;
  // No response or response status.
  if (typeof error !== 'object' || !(response = error.response) || !(status = response.status)) {
    return ret;
  }

  if (status === 413) {
    ret.type = FILE_TOO_LARGE;
    ret.message = 'The uploaded file was too large.';
  }

  if (!(data = response.data)) {
    return ret;
  }

  if (data.message) {
    ret.message = data.message;
  }

  if (status === 404) {
    ret.type = NOT_FOUND;
  }

  if (status === 422) {
    ret.type = VALIDATION_FAILURE;
    ret.data = data.errors || {};
  }

  return ret;
}

export function reportError(error) {
  if (!isProduction) {
    //eslint-disable-next-line no-console
    console.error(error);
  }
  if (normalizeError(error).type === NOT_FOUND) {
    // Do not report these errors.
    return;
  }

  captureException(error);
}
