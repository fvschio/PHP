<?php
class FormCodeReader extends TPage
{
    private $form;

    public function __construct()
    {
        parent::__construct();

        $this->form = new BootstrapFormBuilder;
        $this->form->setFormTitle('Leitura de barcode e qrcode');

        $barcode = new TBarCodeInputReader('barcode');
        $qrcode  = new TQRCodeInputReader('qrcode');

        $barcode->setSize('100%');
        $qrcode->setSize('100%');

        $this->form->addFields( [new TLabel('Barcode')], [$barcode] );
        $this->form->addFields( [new TLabel('QRCode')],  [$qrcode] );

        $this->form->addAction('Enviar', new TAction( [$this, 'onSend'] ), 'far:check-circle');
        
        parent::add($this->form);
    }
    public function onSend($param)
    {
        $data = $this->form->getData();
        $this->form->setData($data);
        new TMessage('info', ' Barcode: ' . $data->barcode . ' QRCode: ' . $data->qrcode );
    }
}