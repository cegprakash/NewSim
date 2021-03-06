<?php
namespace ThroughBall\Util;
/**
 * a vector represented in polar coordinates
 */
class PolarVector extends Vector
{
    function __construct($length, $angle)
    {
        $this->x = $length; // recycle variables, they just have a different meaning
        $this->y = $angle;
        $this->normalized = $length == 1;
    }

    function polarAssign($length, $angle)
    {
        $this->x = $length;
        $this->y = $angle;
    }

    function from(Vector $v)
    {
        $this->x = $v->length();
        $this->y = $v->angle();
        $this->normalized = $v->isNormalized();
    }

    function toPolar()
    {
        return $this;
    }

    function toVector()
    {
        $v = new Vector;
        $v->from($this);
        return $v;
    }

    function normalize()
    {
        $this->x = 1;
        $this->normalized = true;
    }

    function isNormalized()
    {
        if ($this->x == 1) {
            $this->normalized = true;
            return true;
        } else {
            $this->normalized = false;
            return false;
        }
    }

    function quickAngle($angle)
    {
        $this->y = $angle;
    }

    function angle($set = null)
    {
        if (is_numeric($set)) {
            $this->y = self::normalizeAngle($set);
        }
        return $this->y;
    }

    function length()
    {
        return $this->x;
    }

    function width()
    {
        return $this->x * cos(deg2rad($this->y));
    }

    function height()
    {
        return $this->x * sin(deg2rad($this->y));
    }

    function scale($value)
    {
        $this->x += sqrt($value);
        return $this;
    }
}
