<?php


namespace Helpers;



use Exception;

class JsonResponse
{
    const MSG_ADDED_SUCCESSFULLY = 'responses.msg_added_successfully';
    const MSG_UPDATED_SUCCESSFULLY = "responses.msg_updated_successfully";
    const MSG_DELETED_SUCCESSFULLY = "responses.msg_deleted_successfully";
    const MSG_DELETE_ERROR_USED_RESOURCE = "responses.msg_delete_error_used_resource";
    const MSG_UPDATE_ERROR_USED_RESOURCE = "responses.msg_update_error_used_resource";
    const MSG_NOT_ALLOWED = "responses.msg_not_allowed";
    const MSG_NOT_AUTHORIZED = "responses.msg_not_authorized";
    const MSG_NOT_AUTHENTICATED = "responses.msg_not_authenticated";
    const MSG_NOT_FOUND = "responses.msg_not_found";
    const MSG_USER_NOT_FOUND = "responses.msg_user_not_found";
    const MSG_USER_NOT_ENABLED = "responses.msg_user_not_enabled";
    const MSG_WRONG_PASSWORD = "responses.msg_wrong_password";
    const MSG_SUCCESS = "responses.msg_success";
    const MSG_FAILED = "responses.msg_failed";
    const MSG_LOGIN_SUCCESSFULLY = "responses.msg_login_successfully";
    const MSG_LOGIN_FAILED = "responses.msg_login_failed";
    const MSG_FILE_NOT_FOUND = "responses.msg_file_not_found";
    const MSG_FOLDER_ALREADY_EXISTS= "responses.msg_folder_already_exists";
    const MSG_FOLDER_NOT_FOUND="responses.msg_folder_not_found";
    const MSG_NOT_FOUND_MODIFICATION = "responses.msg_not_found_modification";
    const MSG_NOT_FOUND_VERSION = "responses.msg_not_found_version";
    const MSG_NOT_FOUND_NOTE = "responses.msg_not_found_note";
    const MSG_NOT_FOUND_PROJECT = "responses.msg_not_found_project";
    const MSG_INVALID_INPUTS = "responses.msg_invalid_input";
    const MSG_REQUIRED = "responses.msg_required";
    const MSG_OPERATION_ALREADY_DONE = "responses.msg_operation_already_done";
    const MSG_ACCOUNT_TYPE_WRONG = "responses.msg_account_type_wrong";
    const MSG_ACCOUNT_DD_TYPE_WRONG = "responses.msg_account_dd_type_wrong";
    const MSG_SUM_CREDIT_DEBIT_NOT_EQUAL = "responses.msg_sum_credit_debit_not_equal";
    const MSG_FAILED_ENTRY_BOTH_DEBIT_AND_CREDIT = "responses.msg_failed_entry_both_debit_and_credit";
    const MSG_ENTRY_MUST_BE_BALANCED = "responses.msg_entry_must_be_balanced";
    const MGS_SUM_DEBIT_CREDIT_MUST_EQUAL = "responses.msg_sum_credit_debit_must_equal";
    const MSG_POSTED_ENTRY_SUCCESSFULLY = "responses.msg_posted_entry_successfully";
    const MSG_NOUN_ACCOUNT = "responses.msg_noun_account";

    /**
     * @param $message
     * @param null $content
     * @param int $status
     * @param string $conventionType
     * @return \Illuminate\Http\JsonResponse
     */
    public static function respondSuccess($message, $content = null,$status = 200,$conventionType = Constants::CONV_CAMEL)
    {
        $contentData = null;
        if (!is_null($content)) {
            switch ($conventionType) {
                case Constants::CONV_CAMEL:
                    $contentData = Mapper::toCamel($content);
                    break;
                case Constants:: CONV_UNDERSCORE:
                    $contentData = $content;
                    break;
                default:
                    $contentData = $content;
            }
        }
        return response()->json([
            'result' => 'success',
            'content' => $contentData,
            'message' => $message,
            'status' => $status
        ]);
    }

    /**
     * @param $message
     * @return \Illuminate\Http\JsonResponse
     */
    public static function respondError($message, $status = 500)
    {
        return response()->json([
            'result' => 'failed',
            'content' => null,
            'message' => $message,
            'status' => $status
        ]);
    }

    public static function downloadFile($url)
    {
        return response()->download(public_path('storage/' . $url));
    }

    public static function downloadProject($zipName)
    {
        $headers = ['Content-Type: application/zip'];
        return response()->download($zipName, '', $headers);
    }

    public static function uploadFile($url)
    {

    }

    /**
     * @param Exception $exception
     * @return mixed|void
     * @author karam mustafa
     * @desc this function used if you have any large validation process and you append
     * errors message to any array , this will determine if error message on json array
     */
    public static function formatExceptionMessage(Exception $exception)
    {
        return gettype(json_decode($exception->getMessage())) == 'array'
            ? json_decode($exception->getMessage())
            : $exception->getMessage();
    }
}
