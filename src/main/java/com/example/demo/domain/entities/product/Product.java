package com.example.demo.domain.entities.product;

public class Product implements IProduct {
    private Long id;
    private String description;
    private String name;
    private Double price;
    private Integer stockQuantity;
    private String category;
    private String imageUrl;
    private Boolean active;
    private java.util.List<String> tags;
    private java.util.List<String> reviews;
    private Double averageRating;
    private java.util.List<String> options;

    public Product(Long id, String description, String name, Double price, Integer stockQuantity, String category, String imageUrl, Boolean active, java.util.List<String> tags, java.util.List<String> reviews, Double averageRating, java.util.List<String> options) {
        this.id = id;
        this.description = description;
        this.name = name;
        this.price = price;
        this.stockQuantity = stockQuantity;
        this.category = category;
        this.imageUrl = imageUrl;
        this.active = active;
        this.tags = tags;
        this.reviews = reviews;
        this.averageRating = averageRating;
        this.options = options;
    }

    @Override
    public Long getId() {
        return id;
    }

    @Override
    public String getDescription() {
        return description;
    }

    @Override
    public String getName() {
        return name;
    }

    @Override
    public Double getPrice() {
        return price;
    }

    @Override
    public Integer getStockQuantity() {
        return stockQuantity;
    }

    @Override
    public String getCategory() {
        return category;
    }

    @Override
    public String getImageUrl() {
        return imageUrl;
    }

    @Override
    public Boolean isActive() {
        return active;
    }

    @Override
    public java.util.List<String> getTags() {
        return tags;
    }

    @Override
    public java.util.List<String> getReviews() {
        return reviews;
    }

    @Override
    public Double getAverageRating() {
        return averageRating;
    }

    @Override
    public java.util.List<String> getOptions() {
        return options;
    }
    
}
