<?php

if (DEV) {
    echo json_encode(['error' => $body['errorMessage'], 'errorObject' => ['message' => $body['errorObject']->getMessage()]]);
} else {
    echo json_encode(['error' => $body['errorMessage']]);
}
