<?php

namespace App\Livewire;

use App\Models\Email;
use Symfony\Component\DomCrawler\Crawler;
use Livewire\Component;

class AdminDash extends Component
{
    protected $listeners = ['emailDataParsed' => 'handleEmailDataParsed'];

    public function render()
    {
        return view('livewire.admin-dash')->extends('layouts/master')->section('content');

    }
    public function emailDataParsed($data)
    {
        $html = $data;
        // dd($html[0]);
        // $crawler = new Crawler($html[0]);
        $data = [];
        // $htmlContent = $html[0];
        foreach ($html as $content) {
            $EmailContent = $this->extractData($content);
            array_push($data, $EmailContent);
            $email = new Email($EmailContent);
            $email->save();
        }
    }
    public function bitcoinData($emailContent)
    {

        $crawler = new Crawler($emailContent);

        // Extract the required data
        $recipient = $this->getNodeValue($crawler, '//td/div[contains(text(), "Bitcoin")]', 'Bitcoin');
        $amount = $this->getNodeValue($crawler, '//td/span[contains(@style, "font-size:65px")]', '$0.00');
        $paymentNote = $this->getNodeValue($crawler, '//td/div[contains(text(), "Market Sell Order")]', 'No Payment Note');
        $identifier = ''; // Assuming identifier needs custom logic to extract
        $status = $this->getNodeValue($crawler, '//td/div[contains(text(), "Completed") or contains(text(), "Received") or contains(text(), "Cash Refunded")]', 'No Status');
        $from = $this->getNodeValue($crawler, '//td/div[contains(text(), "May 5 at 6:35 PM")]', 'No Date');
        $bitcoinAmount = $this->getNodeValue($crawler, '//td/div[contains(text(), "Bitcoin Amount")]/../td[2]', '0 BTC');
        $exchangeRate = $this->getNodeValue($crawler, '//td/div[contains(text(), "Exchange Rate")]/../td[2]', '$0.00');
        $totalSaleAmount = $this->getNodeValue($crawler, '//td/div[contains(text(), "Total Sale Amount")]/../td[2]', '$0.00');
        $fee = $this->getNodeValue($crawler, '//td/div[contains(text(), "Fee")]/../td[2]', '$0.00');
        $total = $this->getNodeValue($crawler, '//td/div[contains(text(), "Total")]/../td[2]', '$0.00');

        return [
            'recipient' => $recipient,
            'amount' => $amount,
            'payment_note' => $paymentNote,
            'identifier' => $identifier,
            'status' => $status,
            'from' => $from,
            'bitcoinAmount' => $bitcoinAmount,
            'exchangeRate' => $exchangeRate,
            'totalSaleAmount' => $totalSaleAmount,
            'fee' => $fee,
            'total' => $total
        ];

    }
    private function extractData($emailContent)
    {
        // Create a new Crawler instance and load the email content
        $crawler = new Crawler($emailContent);

        // Use XPath to extract the necessary data
        $recipient = $this->getNodeValue($crawler, '//div[contains(@style, "overflow:hidden;display:inline-block;font-size:18px;font-weight:500;line-height:24px;letter-spacing:0.2px;color:#333;font-family:-apple-system,BlinkMacSystemFont,Helvetica Neue,Helvetica,Arial,sans-serif;vertical-align:middle")]');
        // dump($recipient);
        $amount = $this->getNodeValue($crawler, '//td[@align="center"]/span');

        $paymentNote = $this->getNodeValue($crawler, '//td[contains(@style, "font-size:16px")]/div');
        $status = $this->getNodeValue($crawler, '//td[contains(@style, "font-size:16px")]/div[contains(text(), "Completed") or contains(text(), "Received") or contains(text(), "Cash Refunded")]');

        $identifier = $this->getNodeValue($crawler, '//div[contains(text(), "#")]');       // For the sender, look for the text "From" and get the adjacent value
        $from = $this->getNodeValue($crawler, '//div[contains(text(), "From")]');

        return [
            'recipient' => $recipient,
            'amount' => $amount,
            'payment_note' => $paymentNote,
            'identifier' => $identifier,
            'status' => $status,
            'from' => $from
        ];
    }

    private function getNodeValue($crawler, $xpath)
    {

        $node = $crawler->filterXPath($xpath)->first();
        if ($node->count() > 0) {
            return $node->text();
        } else {
            return null;
        }
    }
}
