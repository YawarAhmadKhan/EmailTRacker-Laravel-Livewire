<?php

namespace App\Livewire;

use App\Models\Email;
use Symfony\Component\DomCrawler\Crawler;
use Livewire\Component;
use Carbon\Carbon;

class AdminDash extends Component
{
    public $loader = '';
    protected $listeners = ['emailDataParsed' => 'handleEmailDataParsed'];

    public function render()
    {
        return view('livewire.admin-dash')->extends('layouts/master')->section('content');

    }
    public function emailDataParsed($data)
    {

        sleep(1);
        foreach ($data as $content) {
            $EmailContent = $this->extractData($content);
            dump($EmailContent);
            Email::updateOrCreate(
                ['subject' => $EmailContent['subject']],
                [
                    'recipient' => $EmailContent['recipient'],
                    'amount' => $EmailContent['amount'],
                    'payment_note' => $EmailContent['payment_note'],
                    'identifier' => $EmailContent['identifier'],
                    'status' => $EmailContent['status'],
                    'from' => $EmailContent['from'],
                    'refund-note' => $EmailContent['refund-note'],
                    'refund-amount' => $EmailContent['refund-amount'],
                    'app' => $EmailContent['app'],
                    'subject' => $EmailContent['subject'],
                    'date' => $EmailContent['date']
                ]
            );
        }
        flash()->success('Emails Operation completed successfully.');
        $this->render();

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
    public function extractAmount($string)
    {
        $pattern = '/\$\d+(?:,\d{3})*(?:\.\d{2})?/';
        preg_match($pattern, $string, $matches);
        return $matches[0] ?? null;
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
        $from = $this->getNodeValue($crawler, '//td/div[contains(text(), "From")]/../following-sibling::td[1]/div');
        // $date = $this->getNodeValue($crawler, '//div[contains(@class, "gmail_attr")]/text()[contains(., "Date:")]');
        $dateString = $this->getNodeValue($crawler, '//div[contains(@class, "gmail_attr") and .//b[contains(text(), "Cash App")]]/text()[contains(., "Date:")]');
        $subjectraw = $this->getNodeValue($crawler, '//div[contains(@class, "gmail_attr")]/text()[contains(., "Subject:")]');
        $subject = str_replace('Subject: Fwd:', '', $subjectraw);

        // $image = $this->getNodeValue($crawler, '//img', 'src');
        $refundNote = $this->getNodeValue($crawler, '//td[contains(@style, "color:#999;font-family:-apple-system,BlinkMacSystemFont,Helvetica Neue,Helvetica,Arial,sans-serif;font-size:16px;line-height:24px;font-weight:400")]/div');
        $refundamount = $this->extractAmount($refundNote);
        // $app = $this->getNodeValue($crawler, '//b[contains(@class, "gmail_sendername")]');
        $emailfrom = $this->getNodeValue($crawler, '//div[contains(@class, "gmail_attr") and .//b[contains(text(), "Cash App")]]//a[contains(@href, "mailto:")]/@href');
        $app = str_replace('mailto:', '', $emailfrom);
        $dateString = str_replace('Date:', '', $dateString);
        $date = strstr($dateString, ' at', true);
        $carbonDate = Carbon::parse($date)->format('Y-m-d');

        // bitcion//
        $bitcoinAmount = $this->getNodeValue($crawler, '//td[div[text()="Bitcoin Amount"]]/following-sibling::td/div');
        $exchangeRate = $this->getNodeValue($crawler, '//td[div[text()="Exchange Rate"]]/following-sibling::td/div');
        $totalSaleAmount = $this->getNodeValue($crawler, '//td[div[text()="Total Sale Amount"]]/following-sibling::td/div');
        $fee = $this->getNodeValue($crawler, '//td[div[text()="Fee"]]/following-sibling::td/div');
        $total = $this->getNodeValue($crawler, '//td[div[text()="Total"]]/following-sibling::td/div');
        $data = [
            "BitcoinAmount" => $bitcoinAmount,
            "ExchangeRate" => $exchangeRate,
            "TotalSaleAmount" => $totalSaleAmount,
            "Fee" => $fee
        ];
        $sellorderBtc = json_encode($data);
        return [
            'recipient' => $recipient,
            'amount' => $amount,
            'payment_note' => $paymentNote,
            'identifier' => $identifier,
            'status' => $status,
            'from' => $from,
            'app' => $app,
            'refund-note' => $refundNote,
            'refund-amount' => $refundamount,
            'subject' => $subject,
            'date' => $carbonDate,
            'sellorderBtc' => $sellorderBtc
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
