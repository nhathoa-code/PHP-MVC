<?php 

namespace Mvc\Core;

interface Middleware {
    public function handle(Request $request);
}