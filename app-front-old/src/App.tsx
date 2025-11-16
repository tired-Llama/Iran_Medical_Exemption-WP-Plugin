import { useState } from 'react'
import DatePicker from 'react-multi-date-picker'
import persian from 'react-date-object/calendars/persian'
import persian_fa from 'react-date-object/locales/persian_fa'
import React from 'react'

interface ExemptionRecord {
  num: number
  priority: string
  year: string
  section_name: string
  section_code: string
  article: string
  subject: string
  summary: string
  description: string
}

const App = () => {
  const [date, setDate] = useState<string>('')
  const [section, setSection] = useState<string>('')
  const [article, setArticle] = useState<string>('')
  const [results, setResults] = useState<ExemptionRecord[]>([])
  const [loading, setLoading] = useState(false)
  const [message, setMessage] = useState<string>('')

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    setLoading(true)
    setMessage('')

    try {
      // Search CSV data
      const searchResponse = await fetch(
        `${window.medicalExemptionData.apiUrl}search`,
        {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({ date, section, article }),
        }
      )
      const searchData = await searchResponse.json()
      setResults(searchData.data || [])

      // Submit to database
      await fetch(`${window.medicalExemptionData.apiUrl}submit`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ date, section, article }),
      })

      if (searchData.data.length === 0) {
        setMessage('هیچ نتیجه‌ای یافت نشد')
      }
    } catch (error) {
      console.error('Error:', error)
      setMessage('خطا در بارگذاری اطلاعات')
    }

    setLoading(false)
  }

  return (
    <div className="exemption-container">
      <form onSubmit={handleSubmit} className="exemption-form">
        <div className="form-item">
          <label>تاریخ معافیت</label>
          <DatePicker
            placeholder="انتخاب تاریخ"
            calendar={persian}
            locale={persian_fa}
            onChange={(date) => setDate(date?.format('YYYY/MM/DD') || '')}
          />
        </div>

        <div className="form-item">
          <label htmlFor="medical_exemption_section">ماده / بخش</label>
          <input
            type="text"
            id="medical_exemption_section"
            className="form-control"
            autoComplete="off"
            value={section}
            onChange={(e) => setSection(e.target.value)}
          />
        </div>

        <div className="form-item">
          <label htmlFor="medical_exemption_article">بند</label>
          <input
            type="text"
            id="medical_exemption_article"
            className="form-control"
            autoComplete="off"
            value={article}
            onChange={(e) => setArticle(e.target.value)}
          />
        </div>

        <button type="submit" className="submit-button" disabled={loading}>
          {loading ? 'در حال بررسی...' : 'بررسی'}
        </button>
      </form>

      {message && <p className="message">{message}</p>}

      {results.length > 0 && (
        <div className="record-container">
          <div className="record-header">
            <span className="record-field">ردیف</span>
            <span className="record-field">اولویت انتخاب</span>
            <span className="record-field">سال</span>
            <span className="record-field">بخش</span>
            <span className="record-field">ماده</span>
            <span className="record-field">بند</span>
            <span className="record-field extra-wide">موضوع</span>
            <span className="record-field extra-wide">خلاصه</span>
            <span className="record-field extra-wide">شرح</span>
          </div>

          {results.map((record, index) => (
            <div key={index} className="record-row">
              <span className="record-field">{record.num}</span>
              <span className="record-field">{record.priority}</span>
              <span className="record-field">{record.year}</span>
              <span className="record-field">{record.section_name}</span>
              <span className="record-field">{record.section_code}</span>
              <span className="record-field">{record.article}</span>
              <span className="record-field extra-wide">{record.subject}</span>
              <span className="record-field extra-wide">{record.summary}</span>
              <span className="record-field extra-wide">{record.description}</span>
            </div>
          ))}
        </div>
      )}
    </div>
  )
}

export default App