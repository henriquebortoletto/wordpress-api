<?php

function expire_token() {
  return time() + (60 * 60 * 24);
}
