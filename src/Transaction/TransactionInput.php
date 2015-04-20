<?php

namespace BitWasp\Bitcoin\Transaction;

use BitWasp\Bitcoin\Bitcoin;
use BitWasp\Buffertools\Buffer;
use BitWasp\Bitcoin\Script\Script;
use BitWasp\Bitcoin\Script\ScriptInterface;
use BitWasp\Bitcoin\Serializable;
use BitWasp\Bitcoin\Serializer\Transaction\TransactionInputSerializer;

class TransactionInput extends Serializable implements TransactionInputInterface
{
    /**
     * @var string
     */
    protected $txid;

    /**
     * @var string|int
     */
    protected $vout;

    /**
     * @var string|int
     */
    protected $sequence;

    /**
     * @var ScriptInterface
     */
    protected $script;

    /**
     * @var ScriptInterface
     */
    protected $outputScript;

    /**
     * @param string|null $txid
     * @param string|null $vout
     * @param ScriptInterface|Buffer $script
     * @param int $sequence
     */
    public function __construct($txid, $vout, ScriptInterface $script = null, $sequence = self::DEFAULT_SEQUENCE)
    {
        $this->txid = $txid;
        $this->vout = $vout;
        $this->sequence = $sequence;
        $this->script = $script ?: new Script();
    }

    /**
     * Return the transaction ID buffer
     *
     * @return mixed
     */
    public function getTransactionId()
    {
        return $this->txid;
    }

    /**
     * @return mixed
     */
    public function getVout()
    {
        return $this->vout;
    }

    /**
     * @return int
     */
    public function getSequence()
    {
        return $this->sequence;
    }

    /**
     * Return an initialized script. Checks if already has a script
     * object. If not, returns script from scriptBuf (which can simply
     * be null).
     *
     * @return Script
     */
    public function getScript()
    {
        return $this->script;
    }

    /**
     * Set a Script
     *
     * @param ScriptInterface $script
     * @return $this
     */
    public function setScript(ScriptInterface $script)
    {
        $this->script = $script;
        return $this;
    }

    /**
     * Check whether this transaction is a coinbase transaction
     *
     * @return boolean
     */
    public function isCoinbase()
    {
        return $this->getTransactionId() == '0000000000000000000000000000000000000000000000000000000000000000'
            && Bitcoin::getMath()->cmp($this->getVout(), Bitcoin::getMath()->hexDec('ffffffff')) == '0';
    }

    /**
     * @return Buffer
     */
    public function getBuffer()
    {
        $serializer = new TransactionInputSerializer();
        $out = $serializer->serialize($this);
        return $out;
    }
}