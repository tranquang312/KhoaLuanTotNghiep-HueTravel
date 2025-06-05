<?php

namespace App\Traits;

trait FlashMessage
{
    protected function flashSuccess($message)
    {
        session()->flash('success', $message);
    }

    protected function flashError($message)
    {
        session()->flash('error', $message);
    }

    protected function flashWarning($message)
    {
        session()->flash('warning', $message);
    }

    protected function flashInfo($message)
    {
        session()->flash('info', $message);
    }
} 