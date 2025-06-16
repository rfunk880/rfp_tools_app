<?php

use Illuminate\Support\Facades\Session;

function OptionsView($collection, $idKey, $valKey, $default = '', $skip = array())
{
    $str = '';
    if (!empty($collection)) {
        foreach ($collection as $coln) {
            if (in_array($coln->$idKey, $skip)) {
                continue;
            }
            $str .= '<option value="' . $coln->$idKey . '" ';
            if (!is_array($default)) {
                if ($coln->$idKey == $default) {
                    $str .= 'selected="selected"';
                }
            } else {
                if (in_array($coln->$idKey, $default)) {
                    $str .= 'selected="selected"';
                }
            }

            $str .= '>';

            if (is_callable($valKey) && !is_string($valKey)) {
                $str .= $valKey($coln);
            } else {
                $str .= $coln->$valKey;
            }
            $str .= '</option>';
        }
    }
    return $str;
}


function isSelected($option, $val)
{
    return $option == $val ? 'selected="selected"' : '';
}

function isMultiSelected($option, $arr)
{
    $arr = is_array($arr) ? $arr : [$arr];
    return in_array($option, $arr) ? 'selected="selected"' : '';
}

function isChecked($option, $val)
{
    return $option == $val ? 'checked="checked"' : '';
}

function isMultiChecked($option, $arr)
{
    $arr = is_array($arr) ? $arr : [$arr];
    return in_array($option, $arr) ? 'checked="checked"' : '';
}


function limit_words($string, $word_limit)
{
    $words = explode(" ", $string);
    return implode(" ", array_splice($words, 0, $word_limit));
}

function word_limit($string, $limit)
{
    $words = explode(" ", $string);
    if (count($words) > $limit) {
        return implode(" ", array_splice($words, 0, $limit));
    } else {
        return $string;
    }
}

function arrayOptions($array, $selected = null, $useKey = true, $callable = null)
{
    $str = '';
    foreach ($array as $k => $ar) {
        $val = $useKey ? $k : $ar;
        
        $str .= '<option value="' . $val. '" ';
        if ($selected && (is_array($selected) && in_array($val, $selected)) || ($selected == $val)) {
            $str .= 'selected="selected"';
        }
        $str .= '>';

        if (!is_null($callable) && is_callable($callable)) {
            $str .= $callable($ar);
        } else {
            $str .= $ar;
        }
        $str .= '</option>';
    }
    return $str;
}


function ToJs($data)
{
    echo '<script type="text/javascript">
	    /* <![CDATA[ */';
    foreach ($data as $k => $v) {
        echo 'var ' . $k . '= ' . json_encode($v) . ';';
    }
    echo '/* ]]> */
	    </script>';
}

function Notification()
{
    if (Session::has('success')) :
        echo view('components.alert', [
            'type' => 'success',
            'className' => 'alert-success',
            'message' => Session::get('success')
        ])->render();
    elseif (Session::has('info')) :
        echo view('components.alert', [
            'type' => 'info',
            'className' => 'alert-info',
            'message' => Session::get('info')
        ])->render();
    elseif (Session::has('error')) :
        echo view('components.alert', [
            'type' => 'error',
            'className' => 'alert-danger',
            'message' => Session::get('error')
        ])->render();
    endif;
}

function PopupNotification($title, $content)
{
    Session::flash('popupNotification', true);
    Session::flash('popupTitle', $title);
    Session::flash('popupContent', $content);
}

function SuccessTitle($title, $openTag = '<h3 class="text-center text-green bold">', $closeTag = '</h3>')
{
    return $openTag . '<span class="fa fa-check font-20"></span> ' . $title . $closeTag;
}

function ErrorTitle($title, $openTag = '<h3 class="text-center text-red bold">', $closeTag = '</h3>')
{
    return $openTag . '<span class="fa fa-times-circle font-20"></span> ' . $title . $closeTag;
}

function InfoTitle($title, $openTag = '<h3 class="text-center text-blue bold">', $closeTag = '</h3>')
{
    return $openTag . '<span class="fa fa-exclamation-circle font-20"></span> ' . $title . $closeTag;
}

function ReturnNotification($notificationAr, $flashIt = true)
{
    if (@$notificationAr['success']) :
        if ($flashIt) {
            Session::flash('success', $notificationAr['success']);
        }

        return view('components.alert', [
            'type' => 'success',
            'className' => 'alert-success',
            'message' => $notificationAr['success']
        ])->render();


    elseif (@$notificationAr['info']) :
        if ($flashIt) {
            Session::flash('info', $notificationAr['info']);
        }
        return view('components.alert', [
            'type' => 'info',
            'className' => 'alert-info',
            'message' => $notificationAr['info']
        ])->render();

    elseif (@$notificationAr['error']) :
        if ($flashIt) {
            Session::flash('error', $notificationAr['error']);
        }
        return view('components.alert', [
            'type' => 'error',
            'className' => 'alert-danger',
            'message' => $notificationAr['error']
        ])->render();

    endif;
}


function ReturnQuickNotification($notificationAr, $flashIt = true)
{
    if (@$notificationAr['success']) :
        if ($flashIt) {
            Session::flash('success', $notificationAr['success']);
        }
        return array(
            'title' => 'Success',
            'text' => $notificationAr['success'],
            'type' => 'success'
        );
    elseif (@$notificationAr['info']) :
        if ($flashIt) {
            Session::flash('info', $notificationAr['info']);
        }
        return array(
            'title' => 'Information ',
            'text' => $notificationAr['info'],
            'type' => 'info'
        );
    elseif (@$notificationAr['error']) :
        if ($flashIt) {
            Session::flash('error', $notificationAr['error']);
        }
        return array(
            'title' => 'Oops!',
            'text' => $notificationAr['error'],
            'type' => 'danger'
        );
    endif;
}

function ValidationNotification($errors)
{
    if ($errors->count() > 0) :

        foreach ($errors->all(
            '<div class="alert alert-danger alert-dismissible" >
                            Error! <strong>:message</strong>
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>'
        ) as $message) {
            echo $message;
        }
    endif;
}


function ReturnValidationNotification($errors, $view = 'components.validation-errors')
{

    // if ($errors->count() > 0) :
    //     $msg = '<div class="alert alert-dismissible bg-danger w-100 p-3 mb-5">

    //     <h4 class="fw-semibold text-white"> <i class="bi bi-dash-circle fs-2hx text-white me-2 mb-5 mb-sm-0"></i> Error!</h4>
    //     <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
    //     <i class="bi bi-x-lg text-white"><span class="path1"></span><span class="path2"></span></i></button>';
    //     foreach ($errors->all(
    //         '
    //                 <div class="d-block pe-0 pe-sm-10">

    //                     <span class="text-white"> :message</span>
    //                 </div>

    //             '
    //     ) as $message) {
    //         $msg .= $message;
    //     }
    //     $message .= '
    //     </div>';
    //     return $msg;

    // endif;

    return view($view, compact('errors'))->render();
}

function putFormField()
{
    return '<input type="hidden" name="_method" value="put">';
}


if (!function_exists('SN')) :
    function SN($paginator, $sn)
    {
        return (($paginator->currentPage() - 1) * $paginator->perPage()) + $sn;
    }
endif;
