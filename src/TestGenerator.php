<?php

namespace Eduardoarandah\Autohttptests;

class TestGenerator
{
    public function generateTest($request, $response)
    {
        $url    = str_replace(config('app.url') . '/', '', $request->getUri());
        $method = strtolower($request->getMethod());
        $texto  = "";

        //request params
        if (count($request->all())) {
            $requestParams = $this->representArrayKV($request->all());
            $texto .= "\$this->$method('$url',$requestParams)";
        } else {
            $texto .= "\$this->$method('$url')";
        }

        //Status
        $status = $response->getStatusCode();
        $texto .= "\n->assertStatus($status)";

        //Has errors?
        if ($request->getSession() && $request->getSession()->has('errors')) {

            foreach ($request->getSession()->get('errors')->getBags() as $errorBag) {

                info('detalle del bag' . print_r($errorBag, true));
                info('keys' . print_r($errorBag->keys(), true));

                $fieldsWithError = $this->representArray($errorBag->keys());
                $texto .= "\n->assertSessionHasErrors($fieldsWithError)";

            }
        } else {

            //Redirect
            if ($response->isRedirect()) {
                $redirectUrl = $response->headers->get('Location');
                $texto .= "\n->assertRedirect('$redirectUrl')";

            }

        }

        //finish
        $texto .= ";\n";

        return $texto;
    }
    public function representArrayKV($array)
    {
        $out = "[";
        foreach ($array as $key => $value) {
            if (!in_array($key, ['_token', '_method'])) {
                $out .= "\n'$key' => '$value',";
            }
        }
        $out .= "\n]";
        return $out;
    }
    public function representArray($array)
    {
        $out = "[";
        foreach ($array as $key) {
            $out .= "\n'$key',";
        }
        $out .= "\n]";
        return $out;
    }
}
