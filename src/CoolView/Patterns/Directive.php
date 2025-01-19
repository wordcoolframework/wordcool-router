<?php

namespace CoolView\Patterns;

use CoolView\CSRFService\CSRFService;

trait Directive {

    public static function get() : array {

        return [
            // Variables
            'variable' => function ($match) {
                return "<?php echo htmlspecialchars({$match}, ENT_QUOTES, 'UTF-8'); ?>";
            },

            'csrf' => function () {
                return "<input 
                type='hidden' 
                name='_token' 
                value=\"" . htmlspecialchars(CSRFService::getToken(),
                        ENT_QUOTES,
                        'UTF-8'
                    ) . "\"
                >";
            },

            // Asset
            'asset' => function ($match) {
                return "<?php echo \$this->asset({$match}); ?>";
            },

            // Conditional statements
            'if' => function ($match) {
                return "<?php if ({$match}): ?>";
            },
            'elseif' => function ($match) {
                return "<?php elseif ({$match}): ?>";
            },
            'else' => function () {
                return "<?php else: ?>";
            },
            'endif' => function () {
                return "<?php endif; ?>";
            },

            // Loops
            'foreach' => function ($match) {
                return "<?php foreach ({$match}): ?>";
            },
            'endforeach' => function () {
                return "<?php endforeach; ?>";
            },
            'for' => function ($match) {
                return "<?php for ({$match}): ?>";
            },
            'endfor' => function () {
                return "<?php endfor; ?>";
            },
            'while' => function ($match) {
                return "<?php while ({$match}): ?>";
            },
            'endwhile' => function () {
                return "<?php endwhile; ?>";
            },

            // Sections
            'section' => function ($match) {
                return "<?php \$this->startSection({$match}); ?>";
            },
            'endsection' => function () {
                return "<?php \$this->endSection(); ?>";
            },
            'yield' => function ($match) {
                return "<?php echo \$this->yieldSection({$match}); ?>";
            },

            // Extends
            'extends' => function ($match) {
                return "<?php \$this->extend({$match}); ?>";
            },

            // @is directives
            'istrue' => function ($match) {
                return "<?php if (!empty({$match}) && {$match} === true): ?>";
            },
            'isfalse' => function ($match) {
                return "<?php if (!empty({$match}) && {$match} !== true): ?>";
            },
            'isnull' => function ($match) {
                return "<?php if (is_null({$match})): ?>";
            },
            'isnotnull' => function ($match) {
                return "<?php if (!is_null({$match})): ?>";
            },
        ];

    }

}