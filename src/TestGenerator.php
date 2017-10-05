<?php

namespace Eduardoarandah\Autohttptests;

class TestGenerator
{

    public function generate($request, $response)
    {
        if ($this->isUrlBlackListed($request)) {
            return '';
        }
        $text = '$this';
        $text .= $this->getActingAs($request);
        $text .= $this->getMethod($request);
        $text .= $this->getStatusCode($response);
        $text .= $this->getErrors($request, $response);
        $text .= ";\n";

        return $text;
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
    public function shortenUrl($url)
    {
        return str_replace(config('app.url'), '', $url);
    }
    public function isUrlBlackListed($request)
    {
        $shortUrl     = $this->shortenUrl($request->getUri());
        $urlBlacklist = ['_debugbar'];

        //urls blacklist
        foreach ($urlBlacklist as $urlIgnored) {
            if (str_contains($shortUrl, $urlIgnored)) {
                return true;
            }
        }
        return false;
    }
    public function getActingAs($request)
    {
        //acting as user
        if ($request->user()) {
            $user_id = $request->user()->id;
            return "\n->actingAs(User::find($user_id))";
        } else {
            return '';
        }

    }
    public function getMethod($request)
    {
        $shortUrl = $this->shortenUrl($request->getUri());
        //make request
        $method = strtolower($request->getMethod());
        if (count($request->all())) {
            $requestParams = $this->representArrayKV($request->all());
            return "\n->$method('$shortUrl',$requestParams)";
        } else {
            return "\n->$method('$shortUrl')";
        }
    }
    public function getStatusCode($response)
    {
        //Status
        $status = $response->getStatusCode();
        return "\n->assertStatus($status)";

    }
    public function getErrors($request, $response)
    {
        //Has errors?
        if ($request->getSession() && $request->getSession()->has('errors')) {

            foreach ($request->getSession()->get('errors')->getBags() as $errorBag) {

                $fieldsWithError = $this->representArray($errorBag->keys());
                return "\n->assertSessionHasErrors($fieldsWithError)";

            }
        } else {

            //Redirect
            if ($response->isRedirect()) {
                $redirectUrl = $this->shortenUrl($response->headers->get('Location'));
                return "\n->assertRedirect('$redirectUrl')";

            }

        }

    }
}
